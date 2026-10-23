import { Context } from 'hono';
import { getCookie, setCookie, deleteCookie } from 'hono/cookie';
import { getTodayWib } from './utils';

export interface Env {
  DB: D1Database;
  ASSETS: Fetcher;
  BUCKET?: R2Bucket;
  SESSION_SECRET?: string;
}

export interface SessionPayload {
  role: 'customer' | 'admin';
  id: number;
  nama?: string;
  username?: string;
  exp: number;
}

const COOKIE_NAME = 'sportkuy_session';

async function getHmacKey(secret: string): Promise<CryptoKey> {
  const enc = new TextEncoder();
  return crypto.subtle.importKey(
    'raw',
    enc.encode(secret || 'sportkuy-default-secret-key'),
    { name: 'HMAC', hash: 'SHA-256' },
    false,
    ['sign', 'verify']
  );
}

function toBase64Url(bytes: Uint8Array): string {
  let binary = '';
  for (let i = 0; i < bytes.byteLength; i++) {
    binary += String.fromCharCode(bytes[i]);
  }
  return btoa(binary).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
}

function fromBase64Url(str: string): Uint8Array {
  const pad = str.length % 4 === 0 ? '' : '='.repeat(4 - (str.length % 4));
  const b64 = str.replace(/-/g, '+').replace(/_/g, '/') + pad;
  const bin = atob(b64);
  const bytes = new Uint8Array(bin.length);
  for (let i = 0; i < bin.length; i++) {
    bytes[i] = bin.charCodeAt(i);
  }
  return bytes;
}

export async function signSession(payload: SessionPayload, secret: string): Promise<string> {
  const json = JSON.stringify(payload);
  const dataB64 = toBase64Url(new TextEncoder().encode(json));
  const key = await getHmacKey(secret);
  const sigBuf = await crypto.subtle.sign('HMAC', key, new TextEncoder().encode(dataB64));
  const sigB64 = toBase64Url(new Uint8Array(sigBuf));
  return `${dataB64}.${sigB64}`;
}

export async function verifySession(token: string, secret: string): Promise<SessionPayload | null> {
  try {
    const parts = token.split('.');
    if (parts.length !== 2) return null;
    const [dataB64, sigB64] = parts;
    const key = await getHmacKey(secret);
    const valid = await crypto.subtle.verify(
      'HMAC',
      key,
      fromBase64Url(sigB64),
      new TextEncoder().encode(dataB64)
    );
    if (!valid) return null;
    const payload = JSON.parse(new TextDecoder().decode(fromBase64Url(dataB64))) as SessionPayload;
    if (Date.now() > payload.exp) return null;
    return payload;
  } catch {
    return null;
  }
}

export async function getSession(c: Context<{ Bindings: Env }>): Promise<SessionPayload | null> {
  const raw = getCookie(c, COOKIE_NAME);
  if (!raw) return null;
  return verifySession(raw, c.env.SESSION_SECRET || 'sportkuy-edge-hmac-secret-key-2026');
}

export async function setUserSession(c: Context<{ Bindings: Env }>, payload: Omit<SessionPayload, 'exp'>): Promise<void> {
  const fullPayload: SessionPayload = {
    ...payload,
    exp: Date.now() + 7 * 24 * 60 * 60 * 1000, // 7 days
  };
  const token = await signSession(fullPayload, c.env.SESSION_SECRET || 'sportkuy-edge-hmac-secret-key-2026');
  setCookie(c, COOKIE_NAME, token, {
    path: '/',
    httpOnly: true,
    sameSite: 'Lax',
    maxAge: 7 * 24 * 60 * 60,
  });
}

export function clearUserSession(c: Context<{ Bindings: Env }>): void {
  deleteCookie(c, COOKIE_NAME, { path: '/' });
}

/**
 * Anti-php2shell Secure Image Upload Validator & Saver for Cloudflare Workers (D1 + Optional R2)
 * Inspects magic bytes, rejects any PHP/script payloads, generates random filename,
 * and persists in D1 `uploaded_files` (100% Free Tier without credit card) + R2 if bound.
 */
export async function handleSecureImageUpload(
  c: Context<{ Bindings: Env }>,
  file: unknown,
  folder: 'bukti' | 'lapangan' | 'user'
): Promise<string | null> {
  if (!file || typeof file === 'string' || !(file instanceof File)) {
    return null;
  }
  if (file.size <= 0 || file.size > 4 * 1024 * 1024) {
    return null; // Max 4MB
  }

  const buf = await file.arrayBuffer();
  const bytes = new Uint8Array(buf);
  if (bytes.length < 12) return null;

  // 1. Magic Bytes Verification (strictly JPEG, PNG, GIF, WEBP)
  let mime = '';
  let ext = '';

  if (bytes[0] === 0xff && bytes[1] === 0xd8 && bytes[2] === 0xff) {
    mime = 'image/jpeg';
    ext = 'jpg';
  } else if (
    bytes[0] === 0x89 &&
    bytes[1] === 0x50 &&
    bytes[2] === 0x4e &&
    bytes[3] === 0x47 &&
    bytes[4] === 0x0d &&
    bytes[5] === 0x0a &&
    bytes[6] === 0x1a &&
    bytes[7] === 0x0a
  ) {
    mime = 'image/png';
    ext = 'png';
  } else if (
    bytes[0] === 0x47 &&
    bytes[1] === 0x49 &&
    bytes[2] === 0x46 &&
    bytes[3] === 0x38 &&
    (bytes[4] === 0x37 || bytes[4] === 0x39) &&
    bytes[5] === 0x61
  ) {
    mime = 'image/gif';
    ext = 'gif';
  } else if (
    bytes[0] === 0x52 &&
    bytes[1] === 0x49 &&
    bytes[2] === 0x46 &&
    bytes[3] === 0x46 &&
    bytes[8] === 0x57 &&
    bytes[9] === 0x45 &&
    bytes[10] === 0x42 &&
    bytes[11] === 0x50
  ) {
    mime = 'image/webp';
    ext = 'webp';
  } else {
    return null; // Reject any non-image magic bytes
  }

  // 2. Scan raw bytes for embedded PHP/script payloads (anti-php2shell polyglot defense)
  const textSample = new TextDecoder('latin1').decode(bytes);
  if (/<\?php|<\?=|<script\b|__HALT_COMPILER/i.test(textSample)) {
    return null;
  }

  // 3. Generate cryptographically random filename
  const randBytes = new Uint8Array(12);
  crypto.getRandomValues(randBytes);
  const randHex = Array.from(randBytes)
    .map((b) => b.toString(16).padStart(2, '0'))
    .join('');
  const safeFilename = `${randHex}.${ext}`;
  const filePath = `${folder}/${safeFilename}`;

  // 4. Store in Optional R2 if bound, AND in D1 `uploaded_files` table
  if (c.env.BUCKET) {
    await c.env.BUCKET.put(filePath, buf, {
      httpMetadata: { contentType: mime },
    });
  }

  await c.env.DB.prepare(
    `INSERT OR REPLACE INTO uploaded_files (file_path, mime_type, file_data, created_at) VALUES (?, ?, ?, ?)`
  )
    .bind(filePath, mime, buf, getTodayWib())
    .run();

  return safeFilename;
}
