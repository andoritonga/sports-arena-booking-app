-- Cloudflare D1 (SQLite) Schema & Initial Data for SportKuy
-- Converted from project_sport_center.sql

DROP TABLE IF EXISTS admin;
CREATE TABLE admin (
  admin_id INTEGER PRIMARY KEY AUTOINCREMENT,
  admin_nama TEXT NOT NULL,
  admin_username TEXT NOT NULL,
  admin_password TEXT NOT NULL,
  admin_foto TEXT DEFAULT NULL
);

INSERT INTO admin (admin_id, admin_nama, admin_username, admin_password, admin_foto) VALUES
(1,'Administrator','admin','21232f297a57a5a743894a0e4a801fc3','1352025327_avatar.png'),
(8,'sosunser','sosun','14134e4e2a0d3dd5035763936ca75621','924622752_President_University_Logo.png');

DROP TABLE IF EXISTS customer;
CREATE TABLE customer (
  customer_id INTEGER PRIMARY KEY AUTOINCREMENT,
  customer_nama TEXT NOT NULL,
  customer_email TEXT NOT NULL,
  customer_hp TEXT NOT NULL,
  customer_alamat TEXT NOT NULL,
  customer_password TEXT NOT NULL
);

INSERT INTO customer (customer_id, customer_nama, customer_email, customer_hp, customer_alamat, customer_password) VALUES
(8,'Michael Carrick','carras16@gmail.com','081616161616','Villa Mutiara Jaya Blok M89 No 44','e10adc3949ba59abbe56e057f20f883e'),
(9,'Cristiano Ronaldo','ronaldo7@gmail.com','087777777777','Berlin, Italy','41a8cfe1555e6bd0a854357b53ece69a'),
(10,'Sosunser','sosunser@jmail.com','081314567891','DESA asahi','0b5029f7c2b1f8894d5d7a0300cf10a2'),
(11,'Budi Santoso','budi@example.com','081234567890','Jakarta','4e782c983e2a4c7986e48c799ec63aea'),
(13,'Andi Pratama','andi@example.com','081234567890','Bandung','21232f297a57a5a743894a0e4a801fc3');

DROP TABLE IF EXISTS kategori;
CREATE TABLE kategori (
  kategori_id INTEGER PRIMARY KEY AUTOINCREMENT,
  kategori_nama TEXT NOT NULL
);

INSERT INTO kategori (kategori_id, kategori_nama) VALUES
(1,'Tidak Berkategori'),
(10,'Lapangan Futsal'),
(11,'Lapangan Badminton'),
(12,'Lapangan Minisoccer'),
(13,'Lapangan Basket');

DROP TABLE IF EXISTS lapangan;
CREATE TABLE lapangan (
  lapangan_id INTEGER PRIMARY KEY AUTOINCREMENT,
  lapangan_nama TEXT NOT NULL,
  lapangan_kategori INTEGER NOT NULL,
  lapangan_harga INTEGER NOT NULL,
  lapangan_keterangan TEXT NOT NULL,
  lapangan_foto1 TEXT DEFAULT NULL,
  lapangan_foto2 TEXT DEFAULT NULL,
  lapangan_foto3 TEXT DEFAULT NULL
);

INSERT INTO lapangan (lapangan_id, lapangan_nama, lapangan_kategori, lapangan_harga, lapangan_keterangan, lapangan_foto1, lapangan_foto2, lapangan_foto3) VALUES
(14,'Futsal Pro Sintetis Arena',10,150000,'Lapangan Futsal rumput sintetis kelas internasional (25m x 15m) dilengkapi dengan pencahayaan LED 1000 Lux dan sistem peredam kejut.','futsal_pro.jpg','futsal_pro.jpg','futsal_pro.jpg'),
(15,'Futsal Vinyl Competition Court',10,140000,'Lapangan Futsal lantai vinyl rubber mat elastis standard FIFA. Permukaan cepat, ideal untuk turnamen maupun latihan intensif.','futsal_pro.jpg','futsal_pro.jpg','futsal_pro.jpg'),
(16,'Badminton Olympic Mat Court 1',11,65000,'Lapangan Badminton dengan karpet standar BWF, bebas silau dan anti-selip.','badminton_pro.jpg','badminton_pro.jpg','badminton_pro.jpg'),
(17,'Badminton VIP Wooden Court 2',11,75000,'Lapangan Badminton lantai kayu parquet premium dengan fasilitas AC central dan tribun penonton eksekutif.','badminton_pro.jpg','badminton_pro.jpg','badminton_pro.jpg'),
(18,'Grand Mini Soccer Turf Stadium',12,350000,'Lapangan Mini Soccer outdoor rumput sintetis Monofilament (50m x 30m), dilengkapi lampu sorot malam hari dan shower room.','minisoccer_pro.jpg','minisoccer_pro.jpg','minisoccer_pro.jpg'),
(19,'Basketball FIBA Indoor Court',13,200000,'Lapangan Basket indoor lantai kayu maple bersertifikasi FIBA, ring basket adjustable tempered glass, dan papan skor digital.','basketball_pro.jpg','basketball_pro.jpg','basketball_pro.jpg');

DROP TABLE IF EXISTS invoice;
CREATE TABLE invoice (
  invoice_id INTEGER PRIMARY KEY AUTOINCREMENT,
  invoice_lapangan INTEGER NOT NULL,
  invoice_tanggal TEXT NOT NULL,
  invoice_customer INTEGER NOT NULL,
  invoice_nama TEXT NOT NULL,
  invoice_hp TEXT NOT NULL,
  invoice_tgl_main TEXT NOT NULL,
  invoice_jam_mulai TEXT NOT NULL,
  invoice_jam_selesai TEXT NOT NULL,
  invoice_harga INTEGER NOT NULL,
  invoice_total_bayar INTEGER NOT NULL,
  invoice_status INTEGER NOT NULL,
  invoice_bukti TEXT NOT NULL
);

INSERT INTO invoice (invoice_id, invoice_lapangan, invoice_tanggal, invoice_customer, invoice_nama, invoice_hp, invoice_tgl_main, invoice_jam_mulai, invoice_jam_selesai, invoice_harga, invoice_total_bayar, invoice_status, invoice_bukti) VALUES
(78,21,'2021-04-15',8,'Michael Carrick','081616161616','2021-04-15','17.00','19.00',100000,200000,2,''),
(86,21,'2021-08-19',10,'Sosunser','081314567891','2021-08-19','20.00','22.00',100000,200000,2,''),
(87,21,'2021-09-15',10,'Sosunser','081314567891','2021-09-16','16.00','18.00',100000,200000,2,'1048853755.jpg'),
(88,21,'2021-12-18',10,'Sosunser','081314567891','2021-12-19','17.00','19.00',100000,200000,3,'2001382847.jpg'),
(89,16,'2026-09-09',13,'Andi Pratama','081234567890','2026-09-09','15.00','17.00',30000,60000,3,'');

DROP TABLE IF EXISTS jadwal;
CREATE TABLE jadwal (
  jadwal_id INTEGER PRIMARY KEY AUTOINCREMENT,
  jadwal_lapangan INTEGER NOT NULL,
  jadwal_tanggal TEXT NOT NULL,
  jadwal_mulai TEXT NOT NULL,
  jadwal_selesai TEXT NOT NULL,
  jadwal_status TEXT NOT NULL DEFAULT 'TERSEDIA'
);

CREATE INDEX idx_jadwal_lap_tgl ON jadwal(jadwal_lapangan, jadwal_tanggal);

DROP TABLE IF EXISTS komentar;
CREATE TABLE komentar (
  komentar_id INTEGER PRIMARY KEY AUTOINCREMENT,
  komentar_lapangan INTEGER NOT NULL,
  komentar_customer INTEGER NOT NULL,
  komentar_nama TEXT NOT NULL,
  komentar_tanggal TEXT NOT NULL,
  komentar_isi TEXT NOT NULL
);

INSERT INTO komentar (komentar_id, komentar_lapangan, komentar_customer, komentar_nama, komentar_tanggal, komentar_isi) VALUES
(2,21,11,'Budi Santoso','2021-05-14','Bola nya jelek, tolong dibeli lagi bola yang baru dan bagus'),
(3,21,8,'Michael Carrick','2021-05-16','What a field! I can do my best in this beautiful field!'),
(4,21,9,'Cristiano Ronaldo','2021-05-07','Numero uno!!'),
(6,14,11,'Budi Santoso','2021-05-11','I Love You!'),
(9,21,8,'Michael Carrick','2021-05-19','I Love This Game!! Hahahaha'),
(11,16,10,'Sosunser','2021-05-19','Lapangannya kurang bagus, netnya udah ada yg jebol'),
(23,21,10,'Sosunser','2021-12-18','Lapangannya Bagus, baru diperbaiki dan kualitasnya sangat baik');

DROP TABLE IF EXISTS transaksi;
CREATE TABLE transaksi (
  transaksi_id INTEGER PRIMARY KEY AUTOINCREMENT,
  transaksi_invoice INTEGER NOT NULL,
  transaksi_lapangan INTEGER NOT NULL,
  transaksi_jumlah INTEGER NOT NULL,
  transaksi_harga INTEGER NOT NULL
);

INSERT INTO transaksi (transaksi_id, transaksi_invoice, transaksi_lapangan, transaksi_jumlah, transaksi_harga) VALUES
(67,57,16,2,60000),
(68,58,16,1,30000),
(69,59,21,3,300000),
(70,60,15,2,180000),
(71,61,21,1,100000),
(73,63,21,2,200000),
(74,64,21,1,100000),
(75,65,21,1,100000),
(76,66,21,1,100000),
(77,67,21,1,100000),
(78,70,16,-2,-60000);

-- Dynamic Uploaded Files Storage Table (100% Free Tier inside D1, no credit card needed)
DROP TABLE IF EXISTS uploaded_files;
CREATE TABLE uploaded_files (
  file_path TEXT PRIMARY KEY,
  mime_type TEXT NOT NULL,
  file_data BLOB NOT NULL,
  created_at TEXT NOT NULL
);
