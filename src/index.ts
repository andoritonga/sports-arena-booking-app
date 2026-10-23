import { Hono, Context } from 'hono';
import { getCookie, setCookie } from 'hono/cookie';
import {
  Env,
  getSession,
  setUserSession,
  clearUserSession,
  handleSecureImageUpload,
} from './auth';
import {
  LangCode,
} from './i18n';
import {
  renderCustomerLayout,
  renderAdminLayout,
} from './layout';
import {
  escapeHtml,
  numberFormat,
  strPadLeft,
  dateToIndo,
  formatDMY,
  getTodayWib,
  md5,
} from './utils';
import {
  renderIndexPage,
  renderKategoriPage,
  renderDetailPage,
  renderAboutPage,
  renderMasukPage,
  renderDaftarPage,
  renderCheckoutPage,
  renderCustomerDashboardPage,
  renderCustomerPesananPage,
  renderCustomerPembayaranPage,
  renderCustomerInvoicePage,
  renderInvoiceCetakPage,
  renderCustomerPasswordPage,
} from './views_customer';
import {
  renderAdminLoginPage,
  renderAdminDashboard,
  renderAdminTransaksi,
  renderAdminTransaksiHapusKonfir,
  renderAdminTransaksiInvoice,
  renderAdminTransaksiInvoiceCetak,
  renderAdminLapangan,
  renderAdminLapanganTambah,
  renderAdminLapanganEdit,
  renderAdminLapanganHapusKonfir,
  renderAdminKategori,
  renderAdminKategoriTambah,
  renderAdminKategoriEdit,
  renderAdminKategoriHapusKonfir,
  renderAdminCustomer,
  renderAdminCustomerTambah,
  renderAdminCustomerEdit,
  renderAdminCustomerHapusKonfir,
  renderAdminList,
  renderAdminTambah,
  renderAdminEdit,
  renderAdminGantiPassword,
  renderAdminLaporan,
  renderLaporanPrintPage,
} from './views_admin';

const app = new Hono<{ Bindings: Env }>();

// Helper: resolve language preference ('id' or 'en')
function getLang(c: Context<{ Bindings: Env }>): LangCode {
  const queryLang = c.req.query('lang');
  if (queryLang === 'en' || queryLang === 'id') {
    return queryLang;
  }
  const cookieLang = getCookie(c, 'sportkuy_lang');
  if (cookieLang === 'en' || cookieLang === 'id') {
    return cookieLang;
  }
  return 'id';
}

// Helper: dapatkan kategori untuk navbar & footer
async function getGlobalCategories(
  c: Context<{ Bindings: Env }>
): Promise<Array<{ kategori_id: number; kategori_nama: string }>> {
  const { results } = await c.env.DB.prepare(
    'SELECT kategori_id, kategori_nama FROM kategori ORDER BY kategori_nama ASC'
  ).all<{ kategori_id: number; kategori_nama: string }>();

  return (results || []) as Array<{ kategori_id: number; kategori_nama: string }>;
}

// Helper: middleware admin check & load data admin
async function getAdminData(c: Context<{ Bindings: Env }>) {
  const session = await getSession(c);
  if (!session || session.role !== 'admin') {
    return null;
  }
  const row = await c.env.DB.prepare(
    'SELECT * FROM admin WHERE admin_id = ?'
  )
    .bind(session.id)
    .first<any>();

  if (!row) return null;

  return {
    admin_id: row.admin_id as number,
    admin_nama: row.admin_nama as string,
    admin_username: row.admin_username as string,
    admin_foto: (row.admin_foto as string) || null,
  };
}

// Helper: hitung pesanan pending (status 1 = menunggu konfirmasi)
async function getPendingInvoiceCount(c: Context<{ Bindings: Env }>): Promise<number> {
  const row = await c.env.DB.prepare(
    'SELECT COUNT(*) as cnt FROM invoice WHERE invoice_status = 1'
  ).first<{ cnt: number }>();
  return row?.cnt || 0;
}

// ----------------------------------------------------
// 1. LANGUAGE SWITCHER ENDPOINT
// ----------------------------------------------------
app.get('/lang.php', (c) => {
  const lang = c.req.query('lang') === 'en' ? 'en' : 'id';
  setCookie(c, 'sportkuy_lang', lang, {
    path: '/',
    maxAge: 365 * 24 * 3600,
    sameSite: 'Lax',
  });
  const redirect = c.req.query('redirect') || c.req.header('referer') || '/';
  return c.redirect(redirect);
});

// ----------------------------------------------------
// 2. DYNAMIC UPLOADED FILES SERVING (/gambar/*)
// ----------------------------------------------------
app.get('/gambar/:folder/:filename', async (c) => {
  const folder = c.req.param('folder');
  const filename = c.req.param('filename');
  const filePath = `${folder}/${filename}`;

  try {
    // 1. Cek database Cloudflare D1
    const record = await c.env.DB.prepare(
      'SELECT file_data, content_type FROM uploaded_files WHERE file_path = ? LIMIT 1'
    )
      .bind(filePath)
      .first<{ file_data: ArrayBuffer; content_type: string }>();

    if (record && record.file_data) {
      return new Response(record.file_data, {
        status: 200,
        headers: {
          'Content-Type': record.content_type || 'image/jpeg',
          'Cache-Control': 'public, max-age=86400, stale-while-revalidate=604800',
        },
      });
    }

    // 2. Fallback: ambil dari bundle static assets
    if (c.env.ASSETS) {
      const assetResp = await c.env.ASSETS.fetch(c.req.raw);
      if (assetResp.status === 200) {
        return assetResp;
      }
    }
  } catch (err) {
    console.error('Error serving file from D1:', err);
  }

  return c.text('File Not Found', 404);
});

// ----------------------------------------------------
// 3. CUSTOMER / PUBLIC ROUTES
// ----------------------------------------------------

// Home Page
async function handleHomePage(c: Context<{ Bindings: Env }>) {
  const lang = getLang(c);
  const session = await getSession(c);
  const categories = await getGlobalCategories(c);

  const cari = c.req.query('cari') || '';
  const urutan = c.req.query('urutan') || 'terbaru';
  const page = parseInt(c.req.query('page') || '1', 10) || 1;
  const limit = 12;
  const offset = (page - 1) * limit;

  let query = `
    SELECT l.*, k.kategori_nama 
    FROM lapangan l 
    LEFT JOIN kategori k ON l.lapangan_kategori = k.kategori_id
  `;
  const binds: any[] = [];
  if (cari) {
    query += ' WHERE l.lapangan_nama LIKE ? OR l.lapangan_keterangan LIKE ?';
    binds.push(`%${cari}%`, `%${cari}%`);
  }
  if (urutan === 'harga') {
    query += ' ORDER BY l.lapangan_harga ASC';
  } else {
    query += ' ORDER BY l.lapangan_id DESC';
  }

  const countRow = await c.env.DB.prepare(
    `SELECT COUNT(*) as total FROM lapangan ${cari ? 'WHERE lapangan_nama LIKE ? OR lapangan_keterangan LIKE ?' : ''}`
  )
    .bind(...(cari ? [`%${cari}%`, `%${cari}%`] : []))
    .first<{ total: number }>();

  const total = countRow?.total || 0;
  const pages = Math.ceil(total / limit) || 1;

  query += ' LIMIT ? OFFSET ?';
  binds.push(limit, offset);

  const { results: lapanganList } = await c.env.DB.prepare(query)
    .bind(...binds)
    .all();

  const mainContent = renderIndexPage({
    lang,
    cari,
    urutan,
    page,
    pages,
    total,
    categories,
    lapanganList: lapanganList || [],
  });

  const customerData =
    session && session.role === 'customer'
      ? { customer_id: session.id, customer_nama: session.nama || 'Pelanggan' }
      : null;

  const html = renderCustomerLayout({
    reqUrl: c.req.url,
    currentFile: 'index.php',
    lang,
    customer: customerData,
    categories,
    contentHtml: mainContent,
  });

  return c.html(html);
}

app.get('/', handleHomePage);
app.get('/index.php', handleHomePage);

// Lapangan Per Kategori
app.get('/lapangan_kategori.php', async (c) => {
  const lang = getLang(c);
  const session = await getSession(c);
  const catId = parseInt(c.req.query('id') || '0', 10);
  const urutan = c.req.query('urutan') || 'terbaru';
  const page = parseInt(c.req.query('page') || '1', 10) || 1;
  const limit = 12;
  const offset = (page - 1) * limit;

  const categories = await getGlobalCategories(c);
  const currentCategory = await c.env.DB.prepare(
    'SELECT * FROM kategori WHERE kategori_id = ?'
  )
    .bind(catId)
    .first<{ kategori_id: number; kategori_nama: string }>();

  const kategoriNama = currentCategory?.kategori_nama || 'Kategori';

  let orderSql = 'ORDER BY l.lapangan_id DESC';
  if (urutan === 'harga') {
    orderSql = 'ORDER BY l.lapangan_harga ASC';
  }

  const countRow = await c.env.DB.prepare(
    'SELECT COUNT(*) as total FROM lapangan WHERE lapangan_kategori = ?'
  )
    .bind(catId)
    .first<{ total: number }>();

  const total = countRow?.total || 0;
  const pages = Math.ceil(total / limit) || 1;

  const { results: lapanganList } = await c.env.DB.prepare(
    `SELECT l.*, k.kategori_nama 
     FROM lapangan l 
     LEFT JOIN kategori k ON l.lapangan_kategori = k.kategori_id 
     WHERE l.lapangan_kategori = ? 
     ${orderSql}
     LIMIT ? OFFSET ?`
  )
    .bind(catId, limit, offset)
    .all();

  const mainContent = renderKategoriPage({
    lang,
    kategoriId: catId,
    kategoriNama,
    urutan,
    page,
    pages,
    categories,
    lapanganList: lapanganList || [],
  });

  const customerData =
    session && session.role === 'customer'
      ? { customer_id: session.id, customer_nama: session.nama || 'Pelanggan' }
      : null;

  const html = renderCustomerLayout({
    reqUrl: c.req.url,
    currentFile: 'lapangan_kategori.php',
    lang,
    customer: customerData,
    categories,
    contentHtml: mainContent,
  });

  return c.html(html);
});

// Lapangan Detail
app.get('/lapangan_detail.php', async (c) => {
  const lang = getLang(c);
  const session = await getSession(c);
  const categories = await getGlobalCategories(c);
  const id = parseInt(c.req.query('id') || '0', 10);
  const todayYmd = getTodayWib();
  const selectedDate = c.req.query('tgl') || todayYmd;
  const alert = c.req.query('alert');

  const lapangan = await c.env.DB.prepare(
    `SELECT l.*, k.kategori_nama 
     FROM lapangan l 
     LEFT JOIN kategori k ON l.lapangan_kategori = k.kategori_id 
     WHERE l.lapangan_id = ?`
  )
    .bind(id)
    .first();

  if (!lapangan) {
    return c.redirect('/index.php');
  }

  // Ambil booking untuk tanggal yang dipilih (status bukan ditolak / 2)
  const { results: bookings } = await c.env.DB.prepare(
    `SELECT invoice_jam_mulai, invoice_jam_selesai, invoice_nama, invoice_status 
     FROM invoice 
     WHERE invoice_lapangan = ? AND invoice_tgl_main = ? AND invoice_status != 2`
  )
    .bind(id, selectedDate)
    .all<any>();

  // Ambil ulasan / komentar
  const { results: komentarList } = await c.env.DB.prepare(
    'SELECT * FROM komentar WHERE komentar_lapangan = ? ORDER BY komentar_id DESC'
  )
    .bind(id)
    .all();

  // Konstruksi slots jadwal jam 08:00 sampai 22:00
  const slotHours = [
    '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00',
    '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'
  ];

  const slots = slotHours.map((startTime) => {
    const nextH = parseInt(startTime.slice(0, 2), 10) + 1;
    const endTime = `${String(nextH).padStart(2, '0')}:00`;

    const isBooked = (bookings || []).some((b: any) => {
      const bStart = String(b.invoice_jam_mulai || '');
      const bEnd = String(b.invoice_jam_selesai || '');
      return bStart <= startTime && bEnd > startTime;
    });

    return {
      jadwal_mulai: startTime,
      jadwal_selesai: endTime,
      jadwal_status: isBooked ? 'SUDAH DIPESAN' : 'TERSEDIA',
    };
  });

  const mainContent = renderDetailPage({
    lang,
    lapangan,
    tanggal: selectedDate,
    todayYmd,
    slots,
    komentarList: komentarList || [],
    customerId: session && session.role === 'customer' ? session.id : null,
    alert,
  });

  const customerData =
    session && session.role === 'customer'
      ? { customer_id: session.id, customer_nama: session.nama || 'Pelanggan' }
      : null;

  const html = renderCustomerLayout({
    reqUrl: c.req.url,
    currentFile: 'lapangan_detail.php',
    lang,
    customer: customerData,
    categories,
    contentHtml: mainContent,
  });

  return c.html(html);
});

// About Page
app.get('/about.php', async (c) => {
  const lang = getLang(c);
  const session = await getSession(c);
  const categories = await getGlobalCategories(c);

  const mainContent = renderAboutPage();

  const customerData =
    session && session.role === 'customer'
      ? { customer_id: session.id, customer_nama: session.nama || 'Pelanggan' }
      : null;

  const html = renderCustomerLayout({
    reqUrl: c.req.url,
    currentFile: 'about.php',
    lang,
    customer: customerData,
    categories,
    contentHtml: mainContent,
  });

  return c.html(html);
});

// Masuk (Login Customer)
app.get('/masuk.php', async (c) => {
  const lang = getLang(c);
  const session = await getSession(c);
  if (session && session.role === 'customer') {
    return c.redirect('/customer.php');
  }

  const categories = await getGlobalCategories(c);
  const alert = c.req.query('alert');
  const mainContent = renderMasukPage(alert);

  const html = renderCustomerLayout({
    reqUrl: c.req.url,
    currentFile: 'masuk.php',
    lang,
    customer: null,
    categories,
    contentHtml: mainContent,
  });

  return c.html(html);
});

// Proses Login Customer
app.post('/masuk_act.php', async (c) => {
  const body = await c.req.parseBody();
  const identifier = String(body.email || body.username || '').trim();
  const password = String(body.password || '');
  const hashed = md5(password);

  // 1. Cek Customer (by email atau nama)
  const cust = await c.env.DB.prepare(
    'SELECT * FROM customer WHERE (customer_email = ? OR customer_nama = ?) AND customer_password = ?'
  )
    .bind(identifier, identifier, hashed)
    .first<any>();

  if (cust) {
    await setUserSession(c, {
      id: cust.customer_id,
      username: cust.customer_email,
      nama: cust.customer_nama,
      role: 'customer',
    });
    return c.redirect('/customer.php');
  }

  // 2. Cek Admin (kompatibilitas portal masuk.php asli)
  const adm = await c.env.DB.prepare(
    'SELECT * FROM admin WHERE admin_username = ? AND admin_password = ?'
  )
    .bind(identifier, hashed)
    .first<any>();

  if (adm) {
    await setUserSession(c, {
      id: adm.admin_id,
      username: adm.admin_username,
      nama: adm.admin_nama,
      role: 'admin',
    });
    return c.redirect('/admin/index.php');
  }

  return c.redirect('/masuk.php?alert=gagal');
});

// Daftar (Register Customer)
app.get('/daftar.php', async (c) => {
  const lang = getLang(c);
  const session = await getSession(c);
  if (session && session.role === 'customer') {
    return c.redirect('/customer.php');
  }

  const categories = await getGlobalCategories(c);
  const alert = c.req.query('alert');
  const mainContent = renderDaftarPage(alert);

  const html = renderCustomerLayout({
    reqUrl: c.req.url,
    currentFile: 'daftar.php',
    lang,
    customer: null,
    categories,
    contentHtml: mainContent,
  });

  return c.html(html);
});

// Proses Daftar Customer
app.post('/daftar_act.php', async (c) => {
  const body = await c.req.parseBody();
  const nama = String(body.nama || '').trim();
  const email = String(body.email || '').trim();
  const hp = String(body.hp || '').trim();
  const alamat = String(body.alamat || '').trim();
  const password = String(body.password || '');

  if (!nama || !email || !hp || !password) {
    return c.redirect('/daftar.php?alert=gagal');
  }

  const existing = await c.env.DB.prepare(
    'SELECT customer_id FROM customer WHERE customer_email = ?'
  )
    .bind(email)
    .first();

  if (existing) {
    return c.redirect('/daftar.php?alert=duplikat');
  }

  const hashed = md5(password);
  await c.env.DB.prepare(
    `INSERT INTO customer (customer_nama, customer_email, customer_hp, customer_alamat, customer_password)
     VALUES (?, ?, ?, ?, ?)`
  )
    .bind(nama, email, hp, alamat, hashed)
    .run();

  return c.redirect('/masuk.php?alert=terdaftar');
});

// Logout Customer
app.get('/customer_logout.php', (c) => {
  clearUserSession(c);
  return c.redirect('/masuk.php');
});

// Checkout Page
app.get('/checkout.php', async (c) => {
  const lang = getLang(c);
  const session = await getSession(c);

  if (!session || session.role !== 'customer') {
    return c.redirect('/masuk.php?alert=login');
  }

  const categories = await getGlobalCategories(c);
  const idLapangan = parseInt(c.req.query('id') || '0', 10);
  const lapangan = await c.env.DB.prepare(
    `SELECT l.*, k.kategori_nama 
     FROM lapangan l 
     LEFT JOIN kategori k ON l.lapangan_kategori = k.kategori_id 
     WHERE l.lapangan_id = ?`
  )
    .bind(idLapangan)
    .first();

  if (!lapangan) {
    return c.redirect('/index.php');
  }

  const customer = await c.env.DB.prepare(
    'SELECT * FROM customer WHERE customer_id = ?'
  )
    .bind(session.id)
    .first();

  const mainContent = renderCheckoutPage({
    customer: customer || {},
    lapangan,
  });

  const customerData = {
    customer_id: session.id,
    customer_nama: session.nama || 'Pelanggan',
  };

  const html = renderCustomerLayout({
    reqUrl: c.req.url,
    currentFile: 'checkout.php',
    lang,
    customer: customerData,
    categories,
    contentHtml: mainContent,
  });

  return c.html(html);
});

// Proses Checkout Action
app.post('/checkout_act.php', async (c) => {
  const session = await getSession(c);
  if (!session || session.role !== 'customer') {
    return c.redirect('/masuk.php?alert=login');
  }

  const body = await c.req.parseBody();
  const idLapangan = parseInt(String(c.req.query('id') || body.lapangan_id || '0'), 10);
  const jamMulai = String(body.jamMulai || '08:00');
  const jamSelesai = String(body.jamSelesai || '09:00');
  const nama = String(body.nama || session.nama || 'Pelanggan');
  const hp = String(body.hp || '');
  const tglMain = String(body.tglMain || getTodayWib());
  const harga = parseInt(String(body.harga || '0'), 10);

  const startH = parseInt(jamMulai.slice(0, 2), 10);
  const endH = parseInt(jamSelesai.slice(0, 2), 10);
  const durasi = endH > startH ? endH - startH : 1;
  const totalBayar = harga * durasi;
  const tglOrder = getTodayWib();

  const res = await c.env.DB.prepare(
    `INSERT INTO invoice 
     (invoice_lapangan, invoice_tanggal, invoice_customer, invoice_nama, invoice_hp, invoice_tgl_main, invoice_jam_mulai, invoice_jam_selesai, invoice_harga, invoice_total_bayar, invoice_status, invoice_bukti)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, '')`
  )
    .bind(
      idLapangan,
      tglOrder,
      session.id,
      nama,
      hp,
      tglMain,
      jamMulai,
      jamSelesai,
      harga,
      totalBayar
    )
    .run();

  const invoiceId = res.meta.last_row_id;

  await c.env.DB.prepare(
    `INSERT INTO transaksi (transaksi_invoice, transaksi_lapangan, transaksi_jumlah, transaksi_harga)
     VALUES (?, ?, ?, ?)`
  )
    .bind(invoiceId, idLapangan, durasi, harga)
    .run();

  return c.redirect('/customer_pesanan.php?alert=sukses');
});

// Customer Dashboard
app.get('/customer.php', async (c) => {
  const lang = getLang(c);
  const session = await getSession(c);

  if (!session || session.role !== 'customer') {
    return c.redirect('/masuk.php');
  }

  const categories = await getGlobalCategories(c);
  const customer = await c.env.DB.prepare(
    'SELECT * FROM customer WHERE customer_id = ?'
  )
    .bind(session.id)
    .first();

  const mainContent = renderCustomerDashboardPage({
    lang,
    customer: customer || {},
  });

  const customerData = {
    customer_id: session.id,
    customer_nama: session.nama || 'Pelanggan',
  };

  const html = renderCustomerLayout({
    reqUrl: c.req.url,
    currentFile: 'customer.php',
    lang,
    customer: customerData,
    categories,
    contentHtml: mainContent,
  });

  return c.html(html);
});

// Customer Pesanan
app.get('/customer_pesanan.php', async (c) => {
  const lang = getLang(c);
  const session = await getSession(c);

  if (!session || session.role !== 'customer') {
    return c.redirect('/masuk.php');
  }

  const categories = await getGlobalCategories(c);
  const alert = c.req.query('alert');

  const { results: invoices } = await c.env.DB.prepare(
    `SELECT i.*, l.lapangan_nama, l.lapangan_foto1 
     FROM invoice i 
     LEFT JOIN lapangan l ON i.invoice_lapangan = l.lapangan_id 
     WHERE i.invoice_customer = ? 
     ORDER BY i.invoice_id DESC`
  )
    .bind(session.id)
    .all();

  const mainContent = renderCustomerPesananPage({
    lang,
    invoices: invoices || [],
    alert,
  });

  const customerData = {
    customer_id: session.id,
    customer_nama: session.nama || 'Pelanggan',
  };

  const html = renderCustomerLayout({
    reqUrl: c.req.url,
    currentFile: 'customer_pesanan.php',
    lang,
    customer: customerData,
    categories,
    contentHtml: mainContent,
  });

  return c.html(html);
});

// Customer Pembayaran (Upload Bukti)
app.get('/customer_pembayaran.php', async (c) => {
  const lang = getLang(c);
  const session = await getSession(c);

  if (!session || session.role !== 'customer') {
    return c.redirect('/masuk.php');
  }

  const categories = await getGlobalCategories(c);
  const id = parseInt(c.req.query('id') || '0', 10);

  const invoice = await c.env.DB.prepare(
    'SELECT * FROM invoice WHERE invoice_id = ? AND invoice_customer = ?'
  )
    .bind(id, session.id)
    .first();

  if (!invoice) {
    return c.redirect('/customer_pesanan.php');
  }

  const mainContent = renderCustomerPembayaranPage({
    lang,
    invoiceId: id,
    invoice,
  });

  const customerData = {
    customer_id: session.id,
    customer_nama: session.nama || 'Pelanggan',
  };

  const html = renderCustomerLayout({
    reqUrl: c.req.url,
    currentFile: 'customer_pembayaran.php',
    lang,
    customer: customerData,
    categories,
    contentHtml: mainContent,
  });

  return c.html(html);
});

// Proses Upload Bukti Pembayaran
app.post('/customer_pembayaran_act.php', async (c) => {
  const session = await getSession(c);
  if (!session || session.role !== 'customer') {
    return c.redirect('/masuk.php');
  }

  const body = await c.req.parseBody();
  const invoiceId = parseInt(String(body.id || '0'), 10);
  const file = body.bukti;

  const inv = await c.env.DB.prepare(
    'SELECT invoice_id FROM invoice WHERE invoice_id = ? AND invoice_customer = ?'
  )
    .bind(invoiceId, session.id)
    .first();

  if (!inv) {
    return c.redirect('/customer_pesanan.php');
  }

  if (file && file instanceof File && file.size > 0) {
    const safeFilename = await handleSecureImageUpload(c, file, 'bukti');
    if (safeFilename) {
      await c.env.DB.prepare(
        'UPDATE invoice SET invoice_bukti = ?, invoice_status = 1 WHERE invoice_id = ?'
      )
        .bind(safeFilename, invoiceId)
        .run();

      return c.redirect('/customer_pesanan.php?alert=upload');
    }
  }

  return c.redirect(`/customer_pembayaran.php?id=${invoiceId}&alert=gagal`);
});

// Customer Invoice Detail
app.get('/customer_invoice.php', async (c) => {
  const lang = getLang(c);
  const session = await getSession(c);

  if (!session || session.role !== 'customer') {
    return c.redirect('/masuk.php');
  }

  const categories = await getGlobalCategories(c);
  const id = parseInt(c.req.query('id') || '0', 10);
  const invoice = await c.env.DB.prepare(
    'SELECT * FROM invoice WHERE invoice_id = ? AND invoice_customer = ?'
  )
    .bind(id, session.id)
    .first();

  if (!invoice) {
    return c.redirect('/customer_pesanan.php');
  }

  const lapangan = await c.env.DB.prepare(
    `SELECT l.*, k.kategori_nama 
     FROM lapangan l 
     LEFT JOIN kategori k ON l.lapangan_kategori = k.kategori_id 
     WHERE l.lapangan_id = ?`
  )
    .bind((invoice as any).invoice_lapangan)
    .first();

  const mainContent = renderCustomerInvoicePage({
    lang,
    invoice,
    lapangan,
  });

  const customerData = {
    customer_id: session.id,
    customer_nama: session.nama || 'Pelanggan',
  };

  const html = renderCustomerLayout({
    reqUrl: c.req.url,
    currentFile: 'customer_invoice.php',
    lang,
    customer: customerData,
    categories,
    contentHtml: mainContent,
  });

  return c.html(html);
});

// Cetak Invoice Customer Standalone
app.get('/customer_invoice_cetak.php', async (c) => {
  const session = await getSession(c);
  if (!session || session.role !== 'customer') {
    return c.redirect('/masuk.php');
  }

  const id = parseInt(c.req.query('id') || '0', 10);
  const invoice = await c.env.DB.prepare(
    'SELECT * FROM invoice WHERE invoice_id = ? AND invoice_customer = ?'
  )
    .bind(id, session.id)
    .first();

  if (!invoice) {
    return c.text('Invoice tidak ditemukan', 404);
  }

  const lapangan = await c.env.DB.prepare(
    `SELECT l.*, k.kategori_nama 
     FROM lapangan l 
     LEFT JOIN kategori k ON l.lapangan_kategori = k.kategori_id 
     WHERE l.lapangan_id = ?`
  )
    .bind((invoice as any).invoice_lapangan)
    .first();

  const html = renderInvoiceCetakPage({
    invoice,
    lapangan,
  });

  return c.html(html);
});

// Ganti Password Customer
app.get('/customer_password.php', async (c) => {
  const lang = getLang(c);
  const session = await getSession(c);

  if (!session || session.role !== 'customer') {
    return c.redirect('/masuk.php');
  }

  const categories = await getGlobalCategories(c);
  const alert = c.req.query('alert');
  const mainContent = renderCustomerPasswordPage({
    lang,
    alert,
  });

  const customerData = {
    customer_id: session.id,
    customer_nama: session.nama || 'Pelanggan',
  };

  const html = renderCustomerLayout({
    reqUrl: c.req.url,
    currentFile: 'customer_password.php',
    lang,
    customer: customerData,
    categories,
    contentHtml: mainContent,
  });

  return c.html(html);
});

// Proses Ganti Password Customer
app.post('/customer_password_act.php', async (c) => {
  const session = await getSession(c);
  if (!session || session.role !== 'customer') {
    return c.redirect('/masuk.php');
  }

  const body = await c.req.parseBody();
  const passwordLama = String(body.password_lama || '');
  const passwordBaru = String(body.password_baru || '');

  if (!passwordBaru || passwordBaru.length < 5) {
    return c.redirect('/customer_password.php?alert=gagal');
  }

  const cust = await c.env.DB.prepare(
    'SELECT customer_password FROM customer WHERE customer_id = ?'
  )
    .bind(session.id)
    .first<{ customer_password: string }>();

  if (cust && cust.customer_password === md5(passwordLama)) {
    await c.env.DB.prepare(
      'UPDATE customer SET customer_password = ? WHERE customer_id = ?'
    )
      .bind(md5(passwordBaru), session.id)
      .run();

    return c.redirect('/customer_password.php?alert=sukses');
  } else {
    return c.redirect('/customer_password.php?alert=gagal');
  }
});

// Komentar / Rating Baru
app.post('/komentar_act.php', async (c) => {
  const session = await getSession(c);
  const body = await c.req.parseBody();
  const lapanganId = parseInt(String(body.lapangan || '0'), 10);
  const nama = String(body.nama || session?.nama || 'Pelanggan').trim();
  const email = String(body.email || session?.username || '').trim();
  const komentar = String(body.komentar || '').trim();
  const bintang = parseInt(String(body.rating || body.bintang || '5'), 10) || 5;
  const tgl = getTodayWib();

  if (lapanganId > 0 && komentar) {
    await c.env.DB.prepare(
      `INSERT INTO komentar (komentar_lapangan, komentar_nama, komentar_email, komentar_tgl, komentar_isi, komentar_bintang)
       VALUES (?, ?, ?, ?, ?, ?)`
    )
      .bind(lapanganId, nama, email, tgl, komentar, bintang)
      .run();
  }

  return c.redirect(`/lapangan_detail.php?id=${lapanganId}&alert=komentar_sukses`);
});

// Hapus Komentar
app.get('/komentar_hapus.php', async (c) => {
  const session = await getSession(c);
  const komentarId = parseInt(c.req.query('id') || '0', 10);
  const lapanganId = parseInt(c.req.query('lapangan') || '0', 10);

  if (session && komentarId > 0) {
    if (session.role === 'admin') {
      await c.env.DB.prepare('DELETE FROM komentar WHERE komentar_id = ?')
        .bind(komentarId)
        .run();
    } else {
      await c.env.DB.prepare(
        'DELETE FROM komentar WHERE komentar_id = ? AND (komentar_nama = ? OR komentar_email = ?)'
      )
        .bind(komentarId, session.nama, session.username)
        .run();
    }
  }

  return c.redirect(`/lapangan_detail.php?id=${lapanganId}`);
});

// ----------------------------------------------------
// 4. ADMIN PORTAL ROUTES
// ----------------------------------------------------

// Login Admin Page
app.get('/login.php', async (c) => {
  const session = await getSession(c);
  if (session && session.role === 'admin') {
    return c.redirect('/admin/index.php');
  }
  const alert = c.req.query('alert');
  return c.html(renderAdminLoginPage(alert));
});

// Periksa Login Admin Action
app.post('/periksa_login.php', async (c) => {
  const body = await c.req.parseBody();
  const username = String(body.username || '').trim();
  const password = String(body.password || '');
  const hashed = md5(password);

  const adm = await c.env.DB.prepare(
    'SELECT * FROM admin WHERE admin_username = ? AND admin_password = ?'
  )
    .bind(username, hashed)
    .first<any>();

  if (adm) {
    await setUserSession(c, {
      id: adm.admin_id,
      username: adm.admin_username,
      nama: adm.admin_nama,
      role: 'admin',
    });
    return c.redirect('/admin/index.php');
  } else {
    return c.redirect('/login.php?alert=gagal');
  }
});

// Logout Admin
app.get('/admin/logout.php', (c) => {
  clearUserSession(c);
  return c.redirect('/login.php?alert=logout');
});

// Admin Dashboard
async function handleAdminDashboard(c: Context<{ Bindings: Env }>) {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const pendingCount = await getPendingInvoiceCount(c);

  const omsetRow = await c.env.DB.prepare(
    'SELECT SUM(invoice_total_bayar) as total FROM invoice WHERE invoice_status IN (3, 4)'
  ).first<{ total: number }>();
  const totalOmset = omsetRow?.total || 0;

  const invoiceRow = await c.env.DB.prepare(
    'SELECT COUNT(*) as count FROM invoice'
  ).first<{ count: number }>();
  const totalInvoice = invoiceRow?.count || 0;

  const lapanganRow = await c.env.DB.prepare(
    'SELECT COUNT(*) as count FROM lapangan'
  ).first<{ count: number }>();
  const totalLapangan = lapanganRow?.count || 0;

  const customerRow = await c.env.DB.prepare(
    'SELECT COUNT(*) as count FROM customer'
  ).first<{ count: number }>();
  const totalCustomer = customerRow?.count || 0;

  const { results: recentInvoices } = await c.env.DB.prepare(
    `SELECT i.*, l.lapangan_nama 
     FROM invoice i 
     LEFT JOIN lapangan l ON i.invoice_lapangan = l.lapangan_id 
     ORDER BY i.invoice_id DESC LIMIT 5`
  ).all();

  const content = renderAdminDashboard({
    admin,
    totalOmset,
    totalInvoice,
    totalLapangan,
    totalCustomer,
    recentInvoices: recentInvoices || [],
  });

  return c.html(
    renderAdminLayout({
      currentFile: 'index.php',
      admin,
      pendingCount,
      contentHtml: content,
    })
  );
}

app.get('/admin', handleAdminDashboard);
app.get('/admin/index.php', handleAdminDashboard);

// Admin Transaksi
app.get('/admin/transaksi.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const pendingCount = await getPendingInvoiceCount(c);

  const { results: invoices } = await c.env.DB.prepare(
    `SELECT i.*, l.lapangan_nama 
     FROM invoice i 
     LEFT JOIN lapangan l ON i.invoice_lapangan = l.lapangan_id 
     ORDER BY i.invoice_id DESC`
  ).all();

  const content = renderAdminTransaksi(invoices || []);
  return c.html(
    renderAdminLayout({
      currentFile: 'transaksi.php',
      admin,
      pendingCount,
      contentHtml: content,
    })
  );
});

// Update Status Transaksi
app.post('/admin/transaksi_status.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const body = await c.req.parseBody();
  const invoiceId = parseInt(String(body.invoice || '0'), 10);
  const status = parseInt(String(body.status || '0'), 10);

  await c.env.DB.prepare(
    'UPDATE invoice SET invoice_status = ? WHERE invoice_id = ?'
  )
    .bind(status, invoiceId)
    .run();

  const inv = await c.env.DB.prepare(
    'SELECT invoice_lapangan, invoice_tgl_main, invoice_jam_mulai, invoice_jam_selesai FROM invoice WHERE invoice_id = ?'
  )
    .bind(invoiceId)
    .first<any>();

  if (inv) {
    const jadwalStatus = status === 3 || status === 4 ? 'SUDAH DIPESAN' : 'TERSEDIA';
    await c.env.DB.prepare(
      `UPDATE jadwal SET jadwal_status = ? 
       WHERE jadwal_lapangan = ? AND jadwal_tanggal = ? 
         AND jadwal_mulai >= ? AND jadwal_selesai <= ?`
    )
      .bind(
        jadwalStatus,
        inv.invoice_lapangan,
        inv.invoice_tgl_main,
        inv.invoice_jam_mulai,
        inv.invoice_jam_selesai
      )
      .run();
  }

  return c.redirect('/admin/transaksi.php?alert=sukses');
});

// Admin Invoice Detail
app.get('/admin/transaksi_invoice.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const pendingCount = await getPendingInvoiceCount(c);
  const id = parseInt(c.req.query('id') || '0', 10);
  const invoice = await c.env.DB.prepare(
    'SELECT * FROM invoice WHERE invoice_id = ?'
  )
    .bind(id)
    .first();

  if (!invoice) {
    return c.redirect('/admin/transaksi.php');
  }

  const invoiceDetail = await c.env.DB.prepare(
    `SELECT l.*, k.kategori_nama 
     FROM lapangan l 
     LEFT JOIN kategori k ON l.lapangan_kategori = k.kategori_id 
     WHERE l.lapangan_id = ?`
  )
    .bind((invoice as any).invoice_lapangan)
    .first();

  const content = renderAdminTransaksiInvoice({
    invoice,
    invoiceDetail,
  });

  return c.html(
    renderAdminLayout({
      currentFile: 'transaksi.php',
      admin,
      pendingCount,
      contentHtml: content,
    })
  );
});

// Admin Invoice Cetak Standalone
app.get('/admin/transaksi_invoice_cetak.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const id = parseInt(c.req.query('id') || '0', 10);
  const invoice = await c.env.DB.prepare(
    'SELECT * FROM invoice WHERE invoice_id = ?'
  )
    .bind(id)
    .first();

  const invoiceDetail = invoice
    ? await c.env.DB.prepare(
        `SELECT l.*, k.kategori_nama 
         FROM lapangan l 
         LEFT JOIN kategori k ON l.lapangan_kategori = k.kategori_id 
         WHERE l.lapangan_id = ?`
      )
        .bind((invoice as any).invoice_lapangan)
        .first()
    : null;

  return c.html(
    renderAdminTransaksiInvoiceCetak({
      invoice,
      invoiceDetail,
    })
  );
});

// Konfirmasi Hapus Transaksi
app.get('/admin/transaksi_hapus_konfir.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const pendingCount = await getPendingInvoiceCount(c);
  const id = parseInt(c.req.query('id') || '0', 10);
  const content = renderAdminTransaksiHapusKonfir(id);

  return c.html(
    renderAdminLayout({
      currentFile: 'transaksi.php',
      admin,
      pendingCount,
      contentHtml: content,
    })
  );
});

// Hapus Transaksi Action
app.get('/admin/transaksi_hapus.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const id = parseInt(c.req.query('id') || '0', 10);
  if (id > 0) {
    await c.env.DB.prepare('DELETE FROM invoice WHERE invoice_id = ?')
      .bind(id)
      .run();
    await c.env.DB.prepare('DELETE FROM transaksi WHERE transaksi_invoice = ?')
      .bind(id)
      .run();
  }

  return c.redirect('/admin/transaksi.php?alert=hapus');
});

// Admin Lapangan
app.get('/admin/lapangan.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const pendingCount = await getPendingInvoiceCount(c);
  const { results: lapanganList } = await c.env.DB.prepare(
    `SELECT l.*, k.kategori_nama 
     FROM lapangan l 
     LEFT JOIN kategori k ON l.lapangan_kategori = k.kategori_id 
     ORDER BY l.lapangan_id DESC`
  ).all();

  const content = renderAdminLapangan(lapanganList || []);
  return c.html(
    renderAdminLayout({
      currentFile: 'lapangan.php',
      admin,
      pendingCount,
      contentHtml: content,
    })
  );
});

// Tambah Lapangan Page
app.get('/admin/lapangan_tambah.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const pendingCount = await getPendingInvoiceCount(c);
  const categories = await getGlobalCategories(c);
  const content = renderAdminLapanganTambah(categories);

  return c.html(
    renderAdminLayout({
      currentFile: 'lapangan.php',
      admin,
      pendingCount,
      contentHtml: content,
    })
  );
});

// Tambah Lapangan Action
app.post('/admin/lapangan_act.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const body = await c.req.parseBody();
  const nama = String(body.nama || '').trim();
  const kategori = parseInt(String(body.kategori || '1'), 10);
  const harga = parseInt(String(body.harga || '0'), 10);
  const keterangan = String(body.keterangan || '').trim();

  let f1 = '';
  let f2 = '';
  let f3 = '';

  if (body.foto1 && body.foto1 instanceof File && body.foto1.size > 0) {
    f1 = (await handleSecureImageUpload(c, body.foto1, 'lapangan')) || '';
  }
  if (body.foto2 && body.foto2 instanceof File && body.foto2.size > 0) {
    f2 = (await handleSecureImageUpload(c, body.foto2, 'lapangan')) || '';
  }
  if (body.foto3 && body.foto3 instanceof File && body.foto3.size > 0) {
    f3 = (await handleSecureImageUpload(c, body.foto3, 'lapangan')) || '';
  }

  await c.env.DB.prepare(
    `INSERT INTO lapangan (lapangan_nama, lapangan_kategori, lapangan_harga, lapangan_keterangan, lapangan_foto1, lapangan_foto2, lapangan_foto3)
     VALUES (?, ?, ?, ?, ?, ?, ?)`
  )
    .bind(nama, kategori, harga, keterangan, f1, f2, f3)
    .run();

  return c.redirect('/admin/lapangan.php?alert=tambah');
});

// Edit Lapangan Page
app.get('/admin/lapangan_edit.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const pendingCount = await getPendingInvoiceCount(c);
  const id = parseInt(c.req.query('id') || '0', 10);
  const lapangan = await c.env.DB.prepare(
    'SELECT * FROM lapangan WHERE lapangan_id = ?'
  )
    .bind(id)
    .first();

  if (!lapangan) return c.redirect('/admin/lapangan.php');

  const categories = await getGlobalCategories(c);
  const content = renderAdminLapanganEdit(lapangan, categories);

  return c.html(
    renderAdminLayout({
      currentFile: 'lapangan.php',
      admin,
      pendingCount,
      contentHtml: content,
    })
  );
});

// Edit Lapangan Action
app.post('/admin/lapangan_update.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const body = await c.req.parseBody();
  const id = parseInt(String(body.id || '0'), 10);
  const nama = String(body.nama || '').trim();
  const kategori = parseInt(String(body.kategori || '1'), 10);
  const harga = parseInt(String(body.harga || '0'), 10);
  const keterangan = String(body.keterangan || '').trim();

  await c.env.DB.prepare(
    `UPDATE lapangan SET 
      lapangan_nama = ?, lapangan_kategori = ?, lapangan_harga = ?, lapangan_keterangan = ?
     WHERE lapangan_id = ?`
  )
    .bind(nama, kategori, harga, keterangan, id)
    .run();

  if (body.foto1 && body.foto1 instanceof File && body.foto1.size > 0) {
    const f1 = await handleSecureImageUpload(c, body.foto1, 'lapangan');
    if (f1) {
      await c.env.DB.prepare('UPDATE lapangan SET lapangan_foto1 = ? WHERE lapangan_id = ?')
        .bind(f1, id)
        .run();
    }
  }
  if (body.foto2 && body.foto2 instanceof File && body.foto2.size > 0) {
    const f2 = await handleSecureImageUpload(c, body.foto2, 'lapangan');
    if (f2) {
      await c.env.DB.prepare('UPDATE lapangan SET lapangan_foto2 = ? WHERE lapangan_id = ?')
        .bind(f2, id)
        .run();
    }
  }
  if (body.foto3 && body.foto3 instanceof File && body.foto3.size > 0) {
    const f3 = await handleSecureImageUpload(c, body.foto3, 'lapangan');
    if (f3) {
      await c.env.DB.prepare('UPDATE lapangan SET lapangan_foto3 = ? WHERE lapangan_id = ?')
        .bind(f3, id)
        .run();
    }
  }

  return c.redirect('/admin/lapangan.php?alert=update');
});

// Konfirmasi Hapus Lapangan
app.get('/admin/lapangan_hapus_konfir.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const pendingCount = await getPendingInvoiceCount(c);
  const id = parseInt(c.req.query('id') || '0', 10);
  const content = renderAdminLapanganHapusKonfir(id);

  return c.html(
    renderAdminLayout({
      currentFile: 'lapangan.php',
      admin,
      pendingCount,
      contentHtml: content,
    })
  );
});

// Hapus Lapangan Action
app.get('/admin/lapangan_hapus.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const id = parseInt(c.req.query('id') || '0', 10);
  if (id > 0) {
    await c.env.DB.prepare('DELETE FROM lapangan WHERE lapangan_id = ?')
      .bind(id)
      .run();
    await c.env.DB.prepare('DELETE FROM invoice WHERE invoice_lapangan = ?')
      .bind(id)
      .run();
  }

  return c.redirect('/admin/lapangan.php?alert=hapus');
});

// Admin Kategori
app.get('/admin/kategori.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const pendingCount = await getPendingInvoiceCount(c);
  const { results: categories } = await c.env.DB.prepare(
    `SELECT k.*, (SELECT COUNT(*) FROM lapangan l WHERE l.lapangan_kategori = k.kategori_id) as jumlah_lapangan
     FROM kategori k 
     ORDER BY k.kategori_nama ASC`
  ).all();

  const content = renderAdminKategori(categories || []);
  return c.html(
    renderAdminLayout({
      currentFile: 'kategori.php',
      admin,
      pendingCount,
      contentHtml: content,
    })
  );
});

// Tambah Kategori Page
app.get('/admin/kategori_tambah.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const pendingCount = await getPendingInvoiceCount(c);
  const content = renderAdminKategoriTambah();

  return c.html(
    renderAdminLayout({
      currentFile: 'kategori.php',
      admin,
      pendingCount,
      contentHtml: content,
    })
  );
});

// Tambah Kategori Action
app.post('/admin/kategori_act.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const body = await c.req.parseBody();
  const nama = String(body.nama || '').trim();

  if (nama) {
    await c.env.DB.prepare('INSERT INTO kategori (kategori_nama) VALUES (?)')
      .bind(nama)
      .run();
  }

  return c.redirect('/admin/kategori.php?alert=tambah');
});

// Edit Kategori Page
app.get('/admin/kategori_edit.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const pendingCount = await getPendingInvoiceCount(c);
  const id = parseInt(c.req.query('id') || '0', 10);
  const kategori = await c.env.DB.prepare(
    'SELECT * FROM kategori WHERE kategori_id = ?'
  )
    .bind(id)
    .first();

  if (!kategori) return c.redirect('/admin/kategori.php');

  const content = renderAdminKategoriEdit(kategori);
  return c.html(
    renderAdminLayout({
      currentFile: 'kategori.php',
      admin,
      pendingCount,
      contentHtml: content,
    })
  );
});

// Edit Kategori Action
app.post('/admin/kategori_update.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const body = await c.req.parseBody();
  const id = parseInt(String(body.id || '0'), 10);
  const nama = String(body.nama || '').trim();

  if (id > 0 && nama) {
    await c.env.DB.prepare('UPDATE kategori SET kategori_nama = ? WHERE kategori_id = ?')
      .bind(nama, id)
      .run();
  }

  return c.redirect('/admin/kategori.php?alert=update');
});

// Konfirmasi Hapus Kategori
app.get('/admin/kategori_hapus_konfir.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const pendingCount = await getPendingInvoiceCount(c);
  const id = parseInt(c.req.query('id') || '0', 10);
  const content = renderAdminKategoriHapusKonfir(id);

  return c.html(
    renderAdminLayout({
      currentFile: 'kategori.php',
      admin,
      pendingCount,
      contentHtml: content,
    })
  );
});

// Hapus Kategori Action
app.get('/admin/kategori_hapus.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const id = parseInt(c.req.query('id') || '0', 10);
  if (id > 0) {
    const countRow = await c.env.DB.prepare(
      'SELECT COUNT(*) as cnt FROM lapangan WHERE lapangan_kategori = ?'
    )
      .bind(id)
      .first<{ cnt: number }>();

    if (countRow && countRow.cnt > 0) {
      return c.redirect('/admin/kategori.php?alert=masih_digunakan');
    }

    await c.env.DB.prepare('DELETE FROM kategori WHERE kategori_id = ?')
      .bind(id)
      .run();
  }

  return c.redirect('/admin/kategori.php?alert=hapus');
});

// Admin Customer
app.get('/admin/customer.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const pendingCount = await getPendingInvoiceCount(c);
  const { results: customers } = await c.env.DB.prepare(
    'SELECT * FROM customer ORDER BY customer_id DESC'
  ).all();

  const content = renderAdminCustomer(customers || []);
  return c.html(
    renderAdminLayout({
      currentFile: 'customer.php',
      admin,
      pendingCount,
      contentHtml: content,
    })
  );
});

// Tambah Customer Page
app.get('/admin/customer_tambah.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const pendingCount = await getPendingInvoiceCount(c);
  const content = renderAdminCustomerTambah();

  return c.html(
    renderAdminLayout({
      currentFile: 'customer.php',
      admin,
      pendingCount,
      contentHtml: content,
    })
  );
});

// Tambah Customer Action
app.post('/admin/customer_act.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const body = await c.req.parseBody();
  const nama = String(body.nama || '').trim();
  const email = String(body.email || '').trim();
  const hp = String(body.hp || '').trim();
  const alamat = String(body.alamat || '').trim();
  const password = String(body.password || '');
  const hashed = md5(password);

  await c.env.DB.prepare(
    `INSERT INTO customer (customer_nama, customer_email, customer_hp, customer_alamat, customer_password)
     VALUES (?, ?, ?, ?, ?)`
  )
    .bind(nama, email, hp, alamat, hashed)
    .run();

  return c.redirect('/admin/customer.php?alert=tambah');
});

// Edit Customer Page
app.get('/admin/customer_edit.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const pendingCount = await getPendingInvoiceCount(c);
  const id = parseInt(c.req.query('id') || '0', 10);
  const customer = await c.env.DB.prepare(
    'SELECT * FROM customer WHERE customer_id = ?'
  )
    .bind(id)
    .first();

  if (!customer) return c.redirect('/admin/customer.php');

  const content = renderAdminCustomerEdit(customer);
  return c.html(
    renderAdminLayout({
      currentFile: 'customer.php',
      admin,
      pendingCount,
      contentHtml: content,
    })
  );
});

// Edit Customer Action
app.post('/admin/customer_update.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const body = await c.req.parseBody();
  const id = parseInt(String(body.id || '0'), 10);
  const nama = String(body.nama || '').trim();
  const email = String(body.email || '').trim();
  const hp = String(body.hp || '').trim();
  const alamat = String(body.alamat || '').trim();
  const password = String(body.password || '');

  if (password) {
    const hashed = md5(password);
    await c.env.DB.prepare(
      `UPDATE customer SET 
        customer_nama = ?, customer_email = ?, customer_hp = ?, customer_alamat = ?, customer_password = ?
       WHERE customer_id = ?`
    )
      .bind(nama, email, hp, alamat, hashed, id)
      .run();
  } else {
    await c.env.DB.prepare(
      `UPDATE customer SET 
        customer_nama = ?, customer_email = ?, customer_hp = ?, customer_alamat = ?
       WHERE customer_id = ?`
    )
      .bind(nama, email, hp, alamat, id)
      .run();
  }

  return c.redirect('/admin/customer.php?alert=update');
});

// Konfirmasi Hapus Customer
app.get('/admin/customer_hapus_konfir.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const pendingCount = await getPendingInvoiceCount(c);
  const id = parseInt(c.req.query('id') || '0', 10);
  const content = renderAdminCustomerHapusKonfir(id);

  return c.html(
    renderAdminLayout({
      currentFile: 'customer.php',
      admin,
      pendingCount,
      contentHtml: content,
    })
  );
});

// Hapus Customer Action
app.get('/admin/customer_hapus.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const id = parseInt(c.req.query('id') || '0', 10);
  if (id > 0) {
    await c.env.DB.prepare('DELETE FROM customer WHERE customer_id = ?')
      .bind(id)
      .run();
    await c.env.DB.prepare('DELETE FROM invoice WHERE invoice_customer = ?')
      .bind(id)
      .run();
  }

  return c.redirect('/admin/customer.php?alert=hapus');
});

// Admin List / User Management
app.get('/admin/admin.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const pendingCount = await getPendingInvoiceCount(c);
  const { results: admins } = await c.env.DB.prepare(
    'SELECT * FROM admin ORDER BY admin_id ASC'
  ).all();

  const content = renderAdminList(admins || []);
  return c.html(
    renderAdminLayout({
      currentFile: 'admin.php',
      admin,
      pendingCount,
      contentHtml: content,
    })
  );
});

// Tambah Admin Page
app.get('/admin/admin_tambah.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const pendingCount = await getPendingInvoiceCount(c);
  const content = renderAdminTambah();

  return c.html(
    renderAdminLayout({
      currentFile: 'admin.php',
      admin,
      pendingCount,
      contentHtml: content,
    })
  );
});

// Tambah Admin Action
app.post('/admin/admin_act.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const body = await c.req.parseBody();
  const nama = String(body.nama || '').trim();
  const username = String(body.username || '').trim();
  const password = String(body.password || '');
  const hashed = md5(password);

  let foto = '';
  if (body.foto && body.foto instanceof File && body.foto.size > 0) {
    foto = (await handleSecureImageUpload(c, body.foto, 'user')) || '';
  }

  await c.env.DB.prepare(
    'INSERT INTO admin (admin_nama, admin_username, admin_password, admin_foto) VALUES (?, ?, ?, ?)'
  )
    .bind(nama, username, hashed, foto)
    .run();

  return c.redirect('/admin/admin.php?alert=tambah');
});

// Edit Admin Page
app.get('/admin/admin_edit.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const pendingCount = await getPendingInvoiceCount(c);
  const id = parseInt(c.req.query('id') || '0', 10);
  const targetAdmin = await c.env.DB.prepare(
    'SELECT * FROM admin WHERE admin_id = ?'
  )
    .bind(id)
    .first();

  if (!targetAdmin) return c.redirect('/admin/admin.php');

  const content = renderAdminEdit(targetAdmin);
  return c.html(
    renderAdminLayout({
      currentFile: 'admin.php',
      admin,
      pendingCount,
      contentHtml: content,
    })
  );
});

// Edit Admin Action
app.post('/admin/admin_update.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const body = await c.req.parseBody();
  const id = parseInt(String(body.id || '0'), 10);
  const nama = String(body.nama || '').trim();
  const username = String(body.username || '').trim();
  const password = String(body.password || '');

  if (password) {
    const hashed = md5(password);
    await c.env.DB.prepare(
      'UPDATE admin SET admin_nama = ?, admin_username = ?, admin_password = ? WHERE admin_id = ?'
    )
      .bind(nama, username, hashed, id)
      .run();
  } else {
    await c.env.DB.prepare(
      'UPDATE admin SET admin_nama = ?, admin_username = ? WHERE admin_id = ?'
    )
      .bind(nama, username, id)
      .run();
  }

  if (body.foto && body.foto instanceof File && body.foto.size > 0) {
    const foto = await handleSecureImageUpload(c, body.foto, 'user');
    if (foto) {
      await c.env.DB.prepare('UPDATE admin SET admin_foto = ? WHERE admin_id = ?')
        .bind(foto, id)
        .run();
    }
  }

  return c.redirect('/admin/admin.php?alert=update');
});

// Hapus Admin Action
app.get('/admin/admin_hapus.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const id = parseInt(c.req.query('id') || '0', 10);
  if (id > 0 && id !== admin.admin_id) {
    await c.env.DB.prepare('DELETE FROM admin WHERE admin_id = ?')
      .bind(id)
      .run();
  }

  return c.redirect('/admin/admin.php?alert=hapus');
});

// Ganti Password Admin Page
app.get('/admin/gantipassword.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const pendingCount = await getPendingInvoiceCount(c);
  const alert = c.req.query('alert');
  const content = renderAdminGantiPassword(alert);

  return c.html(
    renderAdminLayout({
      currentFile: 'gantipassword.php',
      admin,
      pendingCount,
      contentHtml: content,
    })
  );
});

// Ganti Password Admin Action
app.post('/admin/gantipassword_act.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const body = await c.req.parseBody();
  const passwordLama = String(body.password_lama || '');
  const passwordBaru = String(body.password_baru || '');

  if (!passwordBaru || passwordBaru.length < 5) {
    return c.redirect('/admin/gantipassword.php?alert=gagal');
  }

  const currentAdmin = await c.env.DB.prepare(
    'SELECT admin_password FROM admin WHERE admin_id = ?'
  )
    .bind(admin.admin_id)
    .first<{ admin_password: string }>();

  if (currentAdmin && currentAdmin.admin_password === md5(passwordLama)) {
    await c.env.DB.prepare(
      'UPDATE admin SET admin_password = ? WHERE admin_id = ?'
    )
      .bind(md5(passwordBaru), admin.admin_id)
      .run();

    return c.redirect('/admin/gantipassword.php?alert=sukses');
  } else {
    return c.redirect('/admin/gantipassword.php?alert=gagal');
  }
});

// Laporan Penjualan
app.get('/admin/laporan.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const pendingCount = await getPendingInvoiceCount(c);
  const tglDari = c.req.query('tanggal_dari') || '';
  const tglSampai = c.req.query('tanggal_sampai') || '';
  const hasFilter = Boolean(tglDari && tglSampai);

  let query = `
    SELECT i.*, l.lapangan_nama, k.kategori_nama 
    FROM invoice i 
    LEFT JOIN lapangan l ON i.invoice_lapangan = l.lapangan_id 
    LEFT JOIN kategori k ON l.lapangan_kategori = k.kategori_id
  `;
  const binds: any[] = [];

  if (hasFilter) {
    query += ' WHERE i.invoice_tanggal >= ? AND i.invoice_tanggal <= ?';
    binds.push(tglDari, tglSampai);
  }
  query += ' ORDER BY i.invoice_id DESC';

  const { results: invoices } = await c.env.DB.prepare(query)
    .bind(...binds)
    .all();

  let totalOmset = 0;
  let totalPending = 0;
  let totalSukses = 0;
  let totalBatal = 0;

  for (const inv of (invoices || []) as any[]) {
    const st = Number(inv.invoice_status);
    const amount = Number(inv.invoice_total_bayar) || 0;
    if (st === 3 || st === 4) {
      totalOmset += amount;
      totalSukses++;
    } else if (st === 0 || st === 1) {
      totalPending++;
    } else if (st === 2) {
      totalBatal++;
    }
  }

  const content = renderAdminLaporan({
    tglDari,
    tglSampai,
    hasFilter,
    stats: {
      totalOmset,
      totalPending,
      totalSukses,
      totalBatal,
    },
    invoices: invoices || [],
  });

  return c.html(
    renderAdminLayout({
      currentFile: 'laporan.php',
      admin,
      pendingCount,
      contentHtml: content,
    })
  );
});

// Cetak Laporan Print Page
app.get('/admin/laporan_print.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const tglDari = c.req.query('tanggal_dari') || '';
  const tglSampai = c.req.query('tanggal_sampai') || '';

  let query = `
    SELECT i.*, l.lapangan_nama, k.kategori_nama 
    FROM invoice i 
    LEFT JOIN lapangan l ON i.invoice_lapangan = l.lapangan_id 
    LEFT JOIN kategori k ON l.lapangan_kategori = k.kategori_id
  `;
  const binds: any[] = [];
  if (tglDari && tglSampai) {
    query += ' WHERE i.invoice_tanggal >= ? AND i.invoice_tanggal <= ?';
    binds.push(tglDari, tglSampai);
  }
  query += ' ORDER BY i.invoice_id DESC';

  const { results: invoices } = await c.env.DB.prepare(query)
    .bind(...binds)
    .all();

  let totalOmset = 0;
  let totalPending = 0;
  let totalSukses = 0;
  let totalBatal = 0;

  for (const inv of (invoices || []) as any[]) {
    const st = Number(inv.invoice_status);
    const amount = Number(inv.invoice_total_bayar) || 0;
    if (st === 3 || st === 4) {
      totalOmset += amount;
      totalSukses++;
    } else if (st === 0 || st === 1) {
      totalPending++;
    } else if (st === 2) {
      totalBatal++;
    }
  }

  return c.html(
    renderLaporanPrintPage({
      tglDari,
      tglSampai,
      stats: { totalOmset, totalPending, totalSukses, totalBatal },
      invoices: invoices || [],
      isPdfStatement: false,
    })
  );
});

// Cetak Laporan PDF Statement Page
app.get('/admin/laporan_pdf.php', async (c) => {
  const admin = await getAdminData(c);
  if (!admin) return c.redirect('/login.php?alert=belum_login');

  const tglDari = c.req.query('tanggal_dari') || '';
  const tglSampai = c.req.query('tanggal_sampai') || '';

  let query = `
    SELECT i.*, l.lapangan_nama, k.kategori_nama 
    FROM invoice i 
    LEFT JOIN lapangan l ON i.invoice_lapangan = l.lapangan_id 
    LEFT JOIN kategori k ON l.lapangan_kategori = k.kategori_id
  `;
  const binds: any[] = [];
  if (tglDari && tglSampai) {
    query += ' WHERE i.invoice_tanggal >= ? AND i.invoice_tanggal <= ?';
    binds.push(tglDari, tglSampai);
  }
  query += ' ORDER BY i.invoice_id DESC';

  const { results: invoices } = await c.env.DB.prepare(query)
    .bind(...binds)
    .all();

  let totalOmset = 0;
  let totalPending = 0;
  let totalSukses = 0;
  let totalBatal = 0;

  for (const inv of (invoices || []) as any[]) {
    const st = Number(inv.invoice_status);
    const amount = Number(inv.invoice_total_bayar) || 0;
    if (st === 3 || st === 4) {
      totalOmset += amount;
      totalSukses++;
    } else if (st === 0 || st === 1) {
      totalPending++;
    } else if (st === 2) {
      totalBatal++;
    }
  }

  return c.html(
    renderLaporanPrintPage({
      tglDari,
      tglSampai,
      stats: { totalOmset, totalPending, totalSukses, totalBatal },
      invoices: invoices || [],
      isPdfStatement: true,
    })
  );
});

export default app;
