import { LangCode, createTranslator } from './i18n';
import { renderCustomerSidebar } from './layout';
import {
  escapeHtml,
  numberFormat,
  strPadLeft,
  dateToIndo,
  formatDMY,
  getCategoryIcon,
  getScheduleDatePills,
} from './utils';

export function renderIndexPage(params: {
  lang: LangCode;
  cari?: string;
  urutan?: string;
  page: number;
  pages: number;
  total: number;
  categories: Array<{ kategori_id: number; kategori_nama: string }>;
  lapanganList: Array<any>;
}): string {
  const { lang, cari, urutan, page, pages, total, categories, lapanganList } = params;
  const __t = createTranslator(lang);
  const katChips = categories
    .slice(0, 5)
    .map(
      (kc) =>
        `<a href="lapangan_kategori.php?id=${kc.kategori_id}" class="hero-chip"><i class="fa fa-bolt" style="color: #60a5fa;"></i> ${escapeHtml(kc.kategori_nama)}</a>`
    )
    .join('\n\t\t\t\t\t');

  const katStrip = categories
    .map((ks) => {
      const cIcon = getCategoryIcon(ks.kategori_nama);
      return `<a href="lapangan_kategori.php?id=${ks.kategori_id}" class="category-pill">
				<i class="fa ${cIcon}"></i> ${escapeHtml(ks.kategori_nama)}
			</a>`;
    })
    .join('\n\t\t\t');

  const cardsHtml = lapanganList
    .map((d) => {
      const imgSrc = d.lapangan_foto1
        ? `gambar/lapangan/${escapeHtml(d.lapangan_foto1)}`
        : 'gambar/sistem/lapangan.png';
      return `<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="product product-single">
									<div class="product-thumb">
										<div class="product-label">
											<span><i class="fa fa-tag"></i> ${escapeHtml(d.kategori_nama)}</span>
										</div>

										<a href="lapangan_detail.php?id=${d.lapangan_id}" class="main-btn quick-view"><i class="fa fa-eye"></i> ${__t('view_detail')}</a>
										<img src="${imgSrc}">
									</div>
									<div class="product-body">
										<h2 class="product-name"><a href="lapangan_detail.php?id=${d.lapangan_id}">${escapeHtml(d.lapangan_nama)}</a></h2>
										<div class="product-price">
											Rp. ${numberFormat(d.lapangan_harga)}
											<span style="font-size: 13px; font-weight: 500; color: #64748b;">${__t('per_session')}</span>
										</div>
										
										<div class="product-btns" style="margin-top: 15px;">
											<a class="primary-btn btn-block text-center" href="lapangan_detail.php?id=${d.lapangan_id}"><i class="fa fa-calendar"></i> ${__t('book_now')}</a>
										</div>
									</div>
								</div>
							</div>`;
    })
    .join('\n');

  let paginationHtml = '';
  for (let i = 1; i <= pages; i++) {
    if (page === i) {
      paginationHtml += `<li class="active">${i}</li>`;
    } else {
      const cParam = cari ? `&cari=${encodeURIComponent(cari)}` : '';
      const uParam = urutan === 'harga' ? '&urutan=harga' : '';
      paginationHtml += `<li><a href="?halaman=${i}${uParam}${cParam}">${i}</a></li>`;
    }
  }

  return `<!-- BREADCRUMB (Hidden on mobile for native app look) -->
<div id="breadcrumb" class="hidden-xs">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<!-- MODERN HERO BANNER -->
<div class="container">
	<div class="modern-hero-section">
		<div class="container">
			<div class="hero-content-wrapper">
				<h1 class="hero-title">${__t('hero_title')}</h1>
				<p class="hero-subtitle">${__t('hero_subtitle')}</p>
				
				<form action="index.php" method="get">
					<div class="hero-search-box">
						<i class="fa fa-search" style="color: #94a3b8; font-size: 18px; margin-left: 15px;"></i>
						<input type="text" name="cari" placeholder="${__t('search_placeholder')}" value="${cari ? escapeHtml(cari) : ''}">
						<button type="submit" class="hero-search-btn"><i class="fa fa-paper-plane"></i> ${__t('search_btn')}</button>
					</div>
				</form>

				<div class="hero-chips">
					<span style="color: #cbd5e1; font-weight: 600; font-size: 13px; align-self: center; margin-right: 5px;">${__t('popular_categories')}</span>
					${katChips}
				</div>
			</div>
		</div>
	</div>

	<!-- APP QUICK CATEGORIES (Native Horizontal Pill Carousel on Mobile) -->
	<div class="visible-xs visible-sm" style="margin-top: 10px; margin-bottom: 5px;">
		<div class="app-categories-strip">
			<a href="index.php" class="category-pill active">
				<i class="fa fa-th-large"></i> ${__t('all')}
			</a>
			${katStrip}
		</div>
	</div>
</div>

<!-- section -->
<div class="section">
	<div class="container">
		<div class="row">
			<div id="main" class="col-md-12">

				<!-- store top filter -->
				<form action="" method="get">
					${cari ? `<input type="hidden" name="cari" value="${escapeHtml(cari)}">` : ''}
					<div class="store-filter clearfix">
						<div class="pull-left" style="line-height: 36px;">
							<h3 style="margin: 0; font-size: 20px; font-weight: 800;">
								${cari ? `${__t('search_results_for')} "${escapeHtml(cari)}"` : __t('all_venues')}
							</h3>
						</div>
						<div class="pull-right">
							<div class="sort-filter">
								<span class="text-uppercase" style="font-weight: 700; margin-right: 8px;">${__t('sort_by')}</span>
								<select class="input" name="urutan" onchange="this.form.submit()">
									<option ${urutan === 'terbaru' ? "selected='selected'" : ''} value="terbaru">${__t('sort_latest')}</option>
									<option ${urutan === 'harga' ? "selected='selected'" : ''} value="harga">${__t('sort_price_low')}</option>
								</select>
							</div>
						</div>
					</div>
				</form>
				<!-- /store top filter -->

				<!-- STORE -->
				<div id="store">
					<div class="row">
						${cardsHtml}
					</div>
					${total === 0 ? `<center><h4>Belum ada Lapangan.</h4></center>` : ''}
				</div>
				<!-- /STORE -->

				<div class="store-filter clearfix">
					<div class="pull-right">
						<ul class="store-pages">
							<li><span class="text-uppercase">Page:</span></li>
							${paginationHtml}
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>`;
}

export function renderKategoriPage(params: {
  lang: LangCode;
  kategoriId: number;
  kategoriNama: string;
  urutan?: string;
  page: number;
  pages: number;
  categories: Array<{ kategori_id: number; kategori_nama: string }>;
  lapanganList: Array<any>;
}): string {
  const { lang, kategoriId, kategoriNama, urutan, page, pages, categories, lapanganList } = params;
  const __t = createTranslator(lang);

  const katStrip = categories
    .map((ks) => {
      const isAct = ks.kategori_id === kategoriId ? 'active' : '';
      const cIcon = getCategoryIcon(ks.kategori_nama);
      return `<a href="lapangan_kategori.php?id=${ks.kategori_id}" class="category-pill ${isAct}">
					<i class="fa ${cIcon}"></i> ${escapeHtml(ks.kategori_nama)}
				</a>`;
    })
    .join('\n\t\t\t\t');

  const cardsHtml = lapanganList
    .map((d) => {
      const imgSrc = d.lapangan_foto1
        ? `gambar/lapangan/${escapeHtml(d.lapangan_foto1)}`
        : 'gambar/sistem/produk.png';
      return `<div class="col-md-3 col-sm-6 col-xs-6">
								<div class="product product-single">
									<div class="product-thumb">
										<div class="product-label">
											<span>${escapeHtml(d.kategori_nama)}</span>
										</div>

										<a href="lapangan_detail.php?id=${d.lapangan_id}" class="main-btn quick-view"><i class="fa fa-eye"></i> ${__t('view_detail')}</a>
										<img src="${imgSrc}" style="height: 250px">
									</div>
									<div class="product-body">
										<h3 class="product-price">
											Rp. ${numberFormat(d.lapangan_harga)},-
											<span style="font-size: 13px; font-weight: 500; color: #64748b;">${__t('per_session')}</span>
										</h3>
										<div class="product-rating">
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star-o empty"></i>
										</div>
										<h2 class="product-name"><a href="lapangan_detail.php?id=${d.lapangan_id}">${escapeHtml(d.lapangan_nama)}</a></h2>
										<div class="product-btns">
											<a class="primary-btn btn-block text-center" href="lapangan_detail.php?id=${d.lapangan_id}"><i class="fa fa-calendar"></i> ${__t('book_now')}</a>
										</div>
									</div>
								</div>
							</div>`;
    })
    .join('\n');

  let paginationHtml = '';
  for (let i = 1; i <= pages; i++) {
    if (page === i) {
      paginationHtml += `<li class="active">${i}</li>`;
    } else {
      const uParam = urutan === 'harga' ? '&urutan=harga' : '';
      paginationHtml += `<li><a href="?id=${kategoriId}&halaman=${i}${uParam}">${i}</a></li>`;
    }
  }

  return `<!-- BREADCRUMB (Hidden on mobile) -->
<div id="breadcrumb" class="hidden-xs">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li class="active">Kategori</li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<!-- section -->
<div class="section">
	<div class="container">
		<!-- APP QUICK CATEGORIES (Native Horizontal Pill Carousel on Mobile) -->
		<div class="visible-xs visible-sm" style="margin-bottom: 15px;">
			<div class="app-categories-strip">
				<a href="index.php" class="category-pill">
					<i class="fa fa-th-large"></i> ${__t('all')}
				</a>
				${katStrip}
			</div>
		</div>

		<div class="row">
			<div id="main" class="col-md-12">
				<div class="pull-left">
					<h4 style="font-weight: 800; color: var(--color-text-main); font-family: 'Outfit', sans-serif;">
						<i class="fa fa-tag" style="color: var(--color-brand-primary);"></i> ${__t('category_venues')} ${escapeHtml(kategoriNama)}
					</h4>
				</div>

				<form action="" method="get">
					<input type="hidden" value="${kategoriId}" name="id">
					<div class="store-filter clearfix">
						<div class="pull-right">
							<div class="sort-filter">
								<span class="text-uppercase" style="font-weight: 700; margin-right: 8px;">${__t('sort_by')}</span>
								<select class="input" name="urutan" onchange="this.form.submit()">
									<option ${urutan === 'terbaru' ? "selected='selected'" : ''} value="terbaru">${__t('sort_latest')}</option>
									<option ${urutan === 'harga' ? "selected='selected'" : ''} value="harga">${__t('sort_price_low')}</option>
								</select>
							</div>
						</div>
					</div>
				</form>

				<div id="store">
					<div class="row">
						${cardsHtml}
						${lapanganList.length === 0 ? `<center><h3>Belum Ada Lapangan</h3></center>` : ''}
					</div>
				</div>

				<div class="store-filter clearfix">
					<div class="pull-right">
						<ul class="store-pages">
							<li><span class="text-uppercase">Page:</span></li>
							${paginationHtml}
						</ul>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>`;
}

export function renderDetailPage(params: {
  lang: LangCode;
  lapangan: any;
  tanggal: string;
  todayYmd: string;
  slots: Array<any>;
  komentarList: Array<any>;
  customerId: number | null;
  alert?: string;
}): string {
  const { lang, lapangan: d, tanggal, todayYmd, slots, komentarList, customerId, alert } = params;
  const __t = createTranslator(lang);

  if (!d) {
    return `<div class="section"><div class="container"><div class="alert alert-danger">Lapangan tidak ditemukan.</div></div></div>`;
  }

  const mainFoto = d.lapangan_foto1
    ? `gambar/lapangan/${escapeHtml(d.lapangan_foto1)}`
    : 'gambar/sistem/lapangan.png';

  const datePillsHtml = getScheduleDatePills(todayYmd)
    .map((p) => {
      const isActive = tanggal === p.date ? 'active' : '';
      return `<a href="lapangan_detail.php?id=${d.lapangan_id}&tgl=${p.date}#schedule-section" class="date-pill ${isActive}">
											<div class="date-day">${p.dayLabel}</div>
											<div class="date-num">${p.dayFormatted}</div>
										</a>`;
    })
    .join('\n\t\t\t\t\t\t\t\t\t\t');

  let availableSlots = 0;
  let bookedSlots = 0;
  slots.forEach((x) => {
    if (x.jadwal_status === 'SUDAH DIPESAN') bookedSlots++;
    else availableSlots++;
  });

  const slotsGridHtml = slots
    .map((x) => {
      const jamInt = parseInt(String(x.jadwal_mulai).slice(0, 2), 10) || 8;
      let period = 'pagi';
      if (jamInt >= 12 && jamInt < 17) period = 'siang';
      else if (jamInt >= 17) period = 'malam';

      const isBooked = x.jadwal_status === 'SUDAH DIPESAN';
      const cardClass = isBooked ? 'booked' : 'available';

      return `<div class="slot-card ${cardClass}" data-period="${period}">
										<div>
											<div class="slot-time">
												<i class="fa fa-clock-o" style="color: ${isBooked ? '#cbd5e1' : '#2563eb'};"></i>
												${escapeHtml(x.jadwal_mulai)} - ${escapeHtml(x.jadwal_selesai)}
											</div>

											<div class="slot-tag">
												${
                          isBooked
                            ? `<i class="fa fa-lock"></i> Terisi`
                            : `<i class="fa fa-circle" style="font-size: 6px; color: #22c55e;"></i> Rp ${numberFormat(d.lapangan_harga / 1000)}k`
                        }
											</div>
										</div>

										<div>
											${
                        isBooked
                          ? `<button class="slot-btn" disabled><i class="fa fa-ban"></i> Terisi</button>`
                          : `<a href="checkout.php?id=${d.lapangan_id}" class="slot-btn">
													Pesan Sesi <i class="fa fa-chevron-right" style="font-size: 10px;"></i>
												</a>`
                      }
										</div>
									</div>`;
    })
    .join('\n\t\t\t\t\t\t\t\t');

  let alertKomentarHtml = '';
  if (alert === 'sukses-komentar') {
    alertKomentarHtml = `<div class='alert alert-success' style='border-radius: 10px; font-weight: 600;'>Komentar Anda berhasil ditambahkan!</div>`;
  } else if (alert === 'sukses-hapus') {
    alertKomentarHtml = `<div class='alert alert-danger' style='border-radius: 10px; font-weight: 600;'>Komentar berhasil dihapus!</div>`;
  }

  const komentarFormHtml = customerId
    ? `<form method="post" action="komentar_act.php?id=${d.lapangan_id}" style="margin-bottom: 30px; background: #f8fafc; padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--color-border-light);">
									${alertKomentarHtml}
									<div class="form-group">
										<label style="font-weight: 700; margin-bottom: 8px;"><i class="fa fa-pencil"></i> Tulis Ulasan / Komentar Anda:</label>
										<textarea class="form-control" name="komentar" rows="3" required="required" placeholder="Tulis pengamalan bermain atau ulasan fasilitas arena ini..."></textarea>
									</div>
									<button type="submit" class="primary-btn"><i class="fa fa-paper-plane"></i> Kirim Ulasan</button>
								</form>`
    : `<div class="alert alert-warning" style="border-radius: 12px; font-weight: 600; margin-bottom: 30px;">
									<i class="fa fa-info-circle"></i> Silahkan <a href="masuk.php" style="color: var(--color-brand-primary); font-weight: 800; text-decoration: underline;">Login</a> untuk dapat memberikan ulasan pada lapangan ini.
								</div>`;

  const komentarItemsHtml =
    komentarList.length === 0
      ? `<p style='color: var(--color-text-muted); font-style: italic;'>Belum ada ulasan untuk lapangan ini. Jadilah yang pertama memberikan ulasan!</p>`
      : komentarList
          .map((k) => {
            const initial = String(k.komentar_nama || 'U')
              .slice(0, 1)
              .toUpperCase();
            const canDelete = customerId && Number(k.komentar_customer) === Number(customerId);
            return `<div style="background: #ffffff; border: 1px solid var(--color-border-light); border-radius: var(--radius-md); padding: 20px; margin-bottom: 16px; box-shadow: var(--shadow-sm);">
									<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
										<div style="display: flex; align-items: center; gap: 10px;">
											<div style="width: 38px; height: 38px; background: var(--color-brand-gradient); border-radius: 50%; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700;">
												${escapeHtml(initial)}
											</div>
											<div>
												<h5 style="margin: 0; font-weight: 700; font-size: 15px; color: var(--color-text-main);">${escapeHtml(k.komentar_nama)}</h5>
												<small style="color: var(--color-text-muted); font-size: 12px;">${dateToIndo(k.komentar_tanggal)}</small>
											</div>
										</div>
										${
                      canDelete
                        ? `<a href="komentar_hapus.php?id=${k.komentar_id}&lapangan=${d.lapangan_id}" style="color: #ef4444; font-weight: 600; font-size: 13px;" onclick="return confirm('Yakin ingin menghapus komentar ini?')">
												<i class="fa fa-trash"></i> Hapus
											</a>`
                        : ''
                    }
									</div>
									<p style="margin: 0; color: var(--color-text-main); font-size: 14px; line-height: 1.6; padding-left: 48px;">
										${escapeHtml(k.komentar_isi)}
									</p>
								</div>`;
          })
          .join('\n');

  return `<!-- BREADCRUMB (Hidden on mobile) -->
<div id="breadcrumb" class="hidden-xs">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li class="active">Detail Lapangan</li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<div class="section">
	<div class="container">
		
		<!-- VENUE HEADER & SHOWCASE ROW -->
		<div class="row" style="margin-bottom: 40px;">
			
			<!-- GALLERY IMAGES -->
			<div class="col-md-6" style="margin-bottom: 30px;">
				<div class="form-box" style="padding: 12px; overflow: hidden;">
					<div id="product-main-view" style="border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 12px;">
						<div class="product-view">
							<img src="${mainFoto}" style="width: 100%; height: 360px; object-fit: cover; border-radius: var(--radius-lg);">
						</div>
					</div>

					<div id="product-view" class="row" style="margin: 0 -5px;">
						<div class="col-xs-4" style="padding: 0 5px;">
							${d.lapangan_foto1 ? `<img src="gambar/lapangan/${escapeHtml(d.lapangan_foto1)}" style="width: 100%; height: 90px; object-fit: cover; border-radius: var(--radius-md); cursor: pointer; border: 2px solid var(--color-brand-primary);">` : ''}
						</div>
						<div class="col-xs-4" style="padding: 0 5px;">
							${d.lapangan_foto2 ? `<img src="gambar/lapangan/${escapeHtml(d.lapangan_foto2)}" style="width: 100%; height: 90px; object-fit: cover; border-radius: var(--radius-md); cursor: pointer;">` : ''}
						</div>
						<div class="col-xs-4" style="padding: 0 5px;">
							${d.lapangan_foto3 ? `<img src="gambar/lapangan/${escapeHtml(d.lapangan_foto3)}" style="width: 100%; height: 90px; object-fit: cover; border-radius: var(--radius-md); cursor: pointer;">` : ''}
						</div>
					</div>
				</div>
			</div>

			<!-- VENUE INFO & BOOKING CARD -->
			<div class="col-md-6">
				<div class="form-box" style="padding: 32px; height: 100%;">
					<div style="margin-bottom: 12px;">
						<span class="badge-status badge-success" style="font-size: 12px; padding: 6px 14px;">
							<i class="fa fa-tag"></i> ${escapeHtml(d.kategori_nama)}
						</span>
					</div>

					<h1 style="font-size: 32px; font-weight: 800; margin: 0 0 14px 0; color: var(--color-text-main);">
						${escapeHtml(d.lapangan_nama)}
					</h1>

					<div style="display: flex; align-items: baseline; gap: 8px; margin-bottom: 24px;">
						<span style="font-size: 32px; font-weight: 800; color: var(--color-brand-primary); font-family: var(--font-heading);">
							Rp. ${numberFormat(d.lapangan_harga)}
						</span>
						<span style="color: var(--color-text-muted); font-size: 15px; font-weight: 600;">${__t('per_session')}</span>
					</div>

					<!-- SPEC CHIPS -->
					<div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 30px;">
						<div style="background: #f1f5f9; padding: 8px 14px; border-radius: var(--radius-md); font-size: 13px; font-weight: 600; color: var(--color-text-main);">
							<i class="fa fa-shield" style="color: var(--color-brand-primary);"></i> ${__t('spec_competition')}
						</div>
						<div style="background: #f1f5f9; padding: 8px 14px; border-radius: var(--radius-md); font-size: 13px; font-weight: 600; color: var(--color-text-main);">
							<i class="fa fa-bolt" style="color: #f59e0b;"></i> ${__t('spec_lighting')}
						</div>
						<div style="background: #f1f5f9; padding: 8px 14px; border-radius: var(--radius-md); font-size: 13px; font-weight: 600; color: var(--color-text-main);">
							<i class="fa fa-shower" style="color: #06b6d4;"></i> ${__t('spec_shower')}
						</div>
						<div style="background: #f1f5f9; padding: 8px 14px; border-radius: var(--radius-md); font-size: 13px; font-weight: 600; color: var(--color-text-main);">
							<i class="fa fa-wifi" style="color: #10b981;"></i> ${__t('spec_wifi')}
						</div>
					</div>

					<!-- ACTION BUTTON -->
					<div style="margin-top: auto;">
						<a class="primary-btn btn-block text-center" href="#schedule-section" style="padding: 16px; font-size: 18px; border-radius: var(--radius-lg);">
							<i class="fa fa-calendar-check-o"></i> ${__t('action_select_schedule')}
						</a>
					</div>
				</div>
			</div>

		</div>

		<!-- TABS SECTION FOR SCHEDULE & DETAILS -->
		<div class="row" id="schedule-section">
			<div class="col-md-12">
				<div class="form-box" style="padding: 30px;">
					
					<ul class="nav nav-tabs" style="border-bottom: 2px solid var(--color-border-light); margin-bottom: 30px;">
						<li class="active"><a data-toggle="tab" href="#tab-jadwal" style="font-weight: 700; font-size: 16px; padding: 12px 20px;"><i class="fa fa-calendar-check-o" style="color: var(--color-brand-primary);"></i> ${__t('tab_schedule')}</a></li>
						<li><a data-toggle="tab" href="#tab-deskripsi" style="font-weight: 700; font-size: 16px; padding: 12px 20px;"><i class="fa fa-info-circle"></i> ${__t('tab_description')}</a></li>
						<li><a data-toggle="tab" href="#tab-komentar" style="font-weight: 700; font-size: 16px; padding: 12px 20px;"><i class="fa fa-comments"></i> ${__t('tab_reviews')}</a></li>
					</ul>

					<div class="tab-content">
						
						<!-- TAB 1: INTERACTIVE JADWAL GRID -->
						<div id="tab-jadwal" class="tab-pane fade in active">
							
							<div style="margin-bottom: 25px;">
								<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
									<h4 style="margin: 0; font-size: 18px; font-weight: 800;"><i class="fa fa-calendar" style="color: var(--color-brand-primary);"></i> ${__t('select_date')}</h4>
									
									<form class="form-inline" action="lapangan_detail.php" method="GET" style="display: inline-flex; gap: 8px;">
										<input type="hidden" name="id" value="${d.lapangan_id}">
										<input autocomplete="off" type="text" value="${escapeHtml(tanggal)}" name="tgl" class="form-control datepicker2" placeholder="Pilih Tanggal Lain" style="max-width: 170px; background: #ffffff; padding: 8px 12px; font-weight: 600;">
										<button type="submit" class="primary-btn" style="padding: 8px 16px;"><i class="fa fa-search"></i> ${__t('show')}</button>
									</form>
								</div>

								<div class="schedule-date-bar">
									${datePillsHtml}
								</div>
							</div>

							<!-- TIME OF DAY FILTER CHIPS -->
							<div style="background: #f8fafc; padding: 18px; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); margin-bottom: 25px;">
								<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
									<div class="time-filter-chips" style="margin-bottom: 0;">
										<button class="time-chip active" onclick="filterSlots('all', this)"><i class="fa fa-list"></i> Semua Sesi</button>
										<button class="time-chip" onclick="filterSlots('pagi', this)"><i class="fa fa-sun-o" style="color: #f59e0b;"></i> Pagi (08:00 - 12:00)</button>
										<button class="time-chip" onclick="filterSlots('siang', this)"><i class="fa fa-cloud" style="color: #06b6d4;"></i> Siang (12:00 - 17:00)</button>
										<button class="time-chip" onclick="filterSlots('malam', this)"><i class="fa fa-moon-o" style="color: #8b5cf6;"></i> Malam Prime (17:00 - 23:00)</button>
									</div>

									<div style="font-weight: 700; font-size: 14px; color: var(--color-text-main);">
										Tanggal Selected: <span style="color: var(--color-brand-primary);">${dateToIndo(tanggal)}</span>
									</div>
								</div>
							</div>

							<!-- SLOT SUMMARY STATS BANNER -->
							<div style="display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap;">
								<div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: var(--radius-md); padding: 8px 16px; font-weight: 700; color: #166534; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
									<span style="width: 8px; height: 8px; background: #22c55e; border-radius: 50%; display: inline-block;"></span>
									${availableSlots} Sesi Tersedia
								</div>
								<div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: var(--radius-md); padding: 8px 16px; font-weight: 700; color: #991b1b; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
									<span style="width: 8px; height: 8px; background: #ef4444; border-radius: 50%; display: inline-block;"></span>
									${bookedSlots} Sesi Terisi
								</div>
							</div>

							<!-- TIME CARDS GRID -->
							<div class="slot-grid" id="slots-container">
								${slotsGridHtml}
							</div>

						</div>

						<!-- TAB 2: DESKRIPSI -->
						<div id="tab-deskripsi" class="tab-pane fade">
							<h3 style="font-size: 20px; font-weight: 800; margin-bottom: 15px;">Informasi & Spesifikasi Arena</h3>
							<div style="font-size: 15px; line-height: 1.8; color: var(--color-text-main);">
								${d.lapangan_keterangan || ''}
							</div>
						</div>

						<!-- TAB 3: KOMENTAR -->
						<div id="tab-komentar" class="tab-pane fade">
							${komentarFormHtml}
							<h4 style="font-size: 18px; font-weight: 800; margin-bottom: 20px;">Ulasan Pelanggan</h4>
							${komentarItemsHtml}
						</div>

					</div>

				</div>
			</div>
		</div>

	</div>
</div>

<script>
function filterSlots(period, btn) {
	var chips = document.querySelectorAll('.time-chip');
	chips.forEach(function(c){ c.classList.remove('active'); });
	btn.classList.add('active');

	var cards = document.querySelectorAll('.slot-card');
	cards.forEach(function(card){
		if(period === 'all' || card.getAttribute('data-period') === period) {
			card.style.display = 'flex';
		} else {
			card.style.display = 'none';
		}
	});
}
</script>`;
}

export function renderAboutPage(): string {
  return `<!-- BREADCRUMB (Hidden on mobile) -->
<div id="breadcrumb" class="hidden-xs">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li class="active">Tentang Kami</li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<!-- ABOUT HERO SECTION -->
<div class="container" style="margin-top: 20px;">
	<div class="modern-hero-section" style="padding: 50px 30px; border-radius: var(--radius-xl); text-center;">
		<div class="container text-center">
			<span style="background: rgba(37,99,235,0.2); color: #60a5fa; padding: 6px 18px; border-radius: 9999px; font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 0.08em; display: inline-block; margin-bottom: 16px;">
				<i class="fa fa-trophy"></i> Tentang SportKuy Arena
			</span>
			<h1 class="hero-title" style="font-size: 42px; font-weight: 800; margin-bottom: 15px;">Membangun Ekosistem Olahraga<br>Masa Depan</h1>
			<p class="hero-subtitle" style="margin: 0 auto 30px auto; font-size: 18px; max-width: 680px; color: #cbd5e1;">
				SportKuy hadir sebagai solusi terdepan dalam penyediaan dan pemesanan lapangan olahraga berkualitas tinggi secara instan, transparan, dan mudah diakses oleh semua pencinta olahraga.
			</p>
		</div>
	</div>
</div>

<!-- MAIN CONTENT SECTION -->
<div class="section" style="padding-top: 20px;">
	<div class="container">
		
		<!-- STATS COUNTER GRID -->
		<div class="row" style="margin-bottom: 50px;">
			<div class="col-md-3 col-sm-6 col-xs-6" style="margin-bottom: 15px;">
				<div class="form-box text-center" style="padding: 24px; background: #ffffff;">
					<div style="font-size: 36px; font-weight: 800; color: var(--color-brand-primary); font-family: var(--font-heading);">6+</div>
					<div style="font-weight: 700; color: var(--color-text-main); margin-top: 4px;">Arena Lapangan</div>
					<small style="color: var(--color-text-muted);">Standar Internasional</small>
				</div>
			</div>
			
			<div class="col-md-3 col-sm-6 col-xs-6" style="margin-bottom: 15px;">
				<div class="form-box text-center" style="padding: 24px; background: #ffffff;">
					<div style="font-size: 36px; font-weight: 800; color: var(--color-brand-accent); font-family: var(--font-heading);">15.000+</div>
					<div style="font-weight: 700; color: var(--color-text-main); margin-top: 4px;">Jam Terbooking</div>
					<small style="color: var(--color-text-muted);">Pemesanan Sukses</small>
				</div>
			</div>

			<div class="col-md-3 col-sm-6 col-xs-6" style="margin-bottom: 15px;">
				<div class="form-box text-center" style="padding: 24px; background: #ffffff;">
					<div style="font-size: 36px; font-weight: 800; color: #8b5cf6; font-family: var(--font-heading);">99.8%</div>
					<div style="font-weight: 700; color: var(--color-text-main); margin-top: 4px;">Kepuasan Customer</div>
					<small style="color: var(--color-text-muted);">Rating Komunitas</small>
				</div>
			</div>

			<div class="col-md-3 col-sm-6 col-xs-6" style="margin-bottom: 15px;">
				<div class="form-box text-center" style="padding: 24px; background: #ffffff;">
					<div style="font-size: 36px; font-weight: 800; color: #06b6d4; font-family: var(--font-heading);">24/7</div>
					<div style="font-weight: 700; color: var(--color-text-main); margin-top: 4px;">Sistem Otomatis</div>
					<small style="color: var(--color-text-muted);">Reservasi Real-Time</small>
				</div>
			</div>
		</div>

		<!-- OUR VALUES / FEATURES -->
		<div class="row" style="margin-bottom: 50px;">
			<div class="col-md-12 text-center" style="margin-bottom: 30px;">
				<h2 style="font-size: 30px; font-weight: 800;">Keunggulan SportKuy Arena</h2>
				<p style="color: var(--color-text-muted); font-size: 16px;">Mengapa ribuan pemain & tim memilih bertanding di arena kami</p>
			</div>

			<div class="col-md-4 col-sm-6" style="margin-bottom: 24px;">
				<div class="form-box" style="padding: 28px; height: 100%;">
					<div style="width: 50px; height: 50px; background: rgba(37,99,235,0.1); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; color: var(--color-brand-primary); font-size: 22px; margin-bottom: 16px;">
						<i class="fa fa-bolt"></i>
					</div>
					<h3 style="font-size: 20px; font-weight: 700; margin-bottom: 10px;">Booking Instan & Cepat</h3>
					<p style="color: var(--color-text-muted); font-size: 14px; line-height: 1.6;">Pilih jadwal dan langsung lakukan booking tanpa harus antre atau menunggu respon manual. Sistem kami memperbarui ketersediaan jadwal secara otomatis.</p>
				</div>
			</div>

			<div class="col-md-4 col-sm-6" style="margin-bottom: 24px;">
				<div class="form-box" style="padding: 28px; height: 100%;">
					<div style="width: 50px; height: 50px; background: rgba(16,185,129,0.1); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; color: var(--color-brand-accent); font-size: 22px; margin-bottom: 16px;">
						<i class="fa fa-star"></i>
					</div>
					<h3 style="font-size: 20px; font-weight: 700; margin-bottom: 10px;">Standar Arena Profesional</h3>
					<p style="color: var(--color-text-muted); font-size: 14px; line-height: 1.6;">Seluruh permukaan lapangan (Rumput Sintetis, Vinyl FIFA, Karpet BWF, Kayu Maple FIBA) menggunakan material kelas dunia untuk kenyamanan dan keamanan bergerak.</p>
				</div>
			</div>

			<div class="col-md-4 col-sm-6" style="margin-bottom: 24px;">
				<div class="form-box" style="padding: 28px; height: 100%;">
					<div style="width: 50px; height: 50px; background: rgba(6,182,212,0.1); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; color: #06b6d4; font-size: 22px; margin-bottom: 16px;">
						<i class="fa fa-shield"></i>
					</div>
					<h3 style="font-size: 20px; font-weight: 700; margin-bottom: 10px;">Fasilitas Lengkap & Nyaman</h3>
					<p style="color: var(--color-text-muted); font-size: 14px; line-height: 1.6;">Dilengkapi dengan ruang ganti ber-AC, shower room air hangat, kantin olahraga, tribun penonton, wifi gratis, dan area parkir luas yang aman.</p>
				</div>
			</div>
		</div>

		<!-- OPERATIONAL INFO & CONTACT CARDS -->
		<div class="row">
			<div class="col-md-12 text-center" style="margin-bottom: 30px;">
				<h2 style="font-size: 30px; font-weight: 800;">Informasi & Kontak Center</h2>
			</div>

			<div class="col-md-4" style="margin-bottom: 20px;">
				<div class="form-box text-center" style="padding: 30px; height: 100%;">
					<div style="width: 60px; height: 60px; background: var(--color-brand-gradient); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; margin-bottom: 16px; box-shadow: 0 4px 12px rgba(37,99,235,0.3);">
						<i class="fa fa-clock-o"></i>
					</div>
					<h3 style="font-size: 18px; font-weight: 700; margin-bottom: 8px;">Jam Operasional</h3>
					<p style="color: var(--color-text-main); font-weight: 600; font-size: 15px; margin: 0;">Senin – Minggu</p>
					<span style="color: var(--color-brand-primary); font-weight: 800; font-size: 16px;">07.00 WIB – 24.00 WIB</span>
				</div>
			</div>

			<div class="col-md-4" style="margin-bottom: 20px;">
				<div class="form-box text-center" style="padding: 30px; height: 100%;">
					<div style="width: 60px; height: 60px; background: var(--color-emerald-gradient); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; margin-bottom: 16px; box-shadow: 0 4px 12px rgba(16,185,129,0.3);">
						<i class="fa fa-map-marker"></i>
					</div>
					<h3 style="font-size: 18px; font-weight: 700; margin-bottom: 8px;">Lokasi Arena</h3>
					<p style="color: var(--color-text-main); font-weight: 600; font-size: 14px; margin: 0;">Jl. Sport Center Boulevard No. 88</p>
					<small style="color: var(--color-text-muted);">Kawasan Olahraga Terpadu</small>
				</div>
			</div>

			<div class="col-md-4" style="margin-bottom: 20px;">
				<div class="form-box text-center" style="padding: 30px; height: 100%;">
					<div style="width: 60px; height: 60px; background: linear-gradient(135deg, #8b5cf6, #6d28d9); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; margin-bottom: 16px; box-shadow: 0 4px 12px rgba(139,92,246,0.3);">
						<i class="fa fa-comments"></i>
					</div>
					<h3 style="font-size: 18px; font-weight: 700; margin-bottom: 8px;">Hubungi Kami</h3>
					<p style="color: var(--color-text-main); font-weight: 600; font-size: 14px; margin: 0;">WhatsApp: +62 813-7223-7921</p>
					<small style="color: var(--color-text-muted);">Email: support@sportkuy.com</small>
				</div>
			</div>

		</div>

	</div>
</div>`;
}

export function renderMasukPage(alert?: string): string {
  let alertHtml = '';
  if (alert === 'terdaftar') {
    alertHtml = `<div class='alert alert-success text-center' style='border-radius: 12px; font-weight: 600;'>Selamat, akun Anda telah terdaftar. Silahkan login.</div>`;
  } else if (alert === 'gagal') {
    alertHtml = `<div class='alert alert-danger text-center' style='border-radius: 12px; font-weight: 600;'>Email/Username atau password salah. Silahkan coba lagi.</div>`;
  } else if (alert === 'login-dulu-pesan') {
    alertHtml = `<div class='alert alert-warning text-center' style='border-radius: 12px; font-weight: 600;'>Silahkan login terlebih dahulu untuk melakukan booking.</div>`;
  } else if (alert === 'login-dulu-komentar') {
    alertHtml = `<div class='alert alert-warning text-center' style='border-radius: 12px; font-weight: 600;'>Silahkan login terlebih dahulu untuk memberikan komentar.</div>`;
  }

  return `<!-- BREADCRUMB (Hidden on mobile) -->
<div id="breadcrumb" class="hidden-xs">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li class="active">Login</li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="form-box">
					<div class="text-center" style="margin-bottom: 25px;">
						<div style="width: 50px; height: 50px; background: var(--color-brand-gradient); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: #fff; font-size: 22px; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(37,99,235,0.3);">
							<i class="fa fa-lock"></i>
						</div>
						<h2 style="font-size: 26px; font-weight: 800; margin-bottom: 6px;">Login Akun</h2>
						<p style="color: var(--color-text-muted);">Masuk ke akun Anda untuk melakukan booking atau mengelola sistem</p>
					</div>
					
					${alertHtml}

					<form action="masuk_act.php" method="post">
						<div class="form-group" style="margin-bottom: 18px;">
							<label for=""><i class="fa fa-user" style="color: var(--color-brand-primary);"></i> Email / Username</label>
							<input type="text" class="form-control" required="required" name="email" placeholder="Masukkan email atau username Anda...">
						</div>

						<div class="form-group" style="margin-bottom: 24px;">
							<label for=""><i class="fa fa-key" style="color: var(--color-brand-primary);"></i> Password</label>
							<input type="password" class="form-control" required="required" name="password" placeholder="Masukkan password Anda...">
						</div>

						<div class="form-group" style="margin-bottom: 0;">
							<button type="submit" class="primary-btn btn-block" style="padding: 12px; font-size: 16px;"><i class="fa fa-sign-in"></i> Masuk Sekarang</button>
							<div class="text-center" style="margin-top: 18px; color: var(--color-text-muted);">
								Belum punya akun? <a href="daftar.php" style="color: var(--color-brand-primary); font-weight: 700;">Daftar Akun Baru</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>`;
}

export function renderDaftarPage(alert?: string): string {
  const alertHtml =
    alert === 'duplikat'
      ? `<div class='alert alert-danger text-center' style='border-radius: 12px; font-weight: 600;'>Maaf, email ini sudah terdaftar. Silahkan gunakan email lain.</div>`
      : '';

  return `<!-- BREADCRUMB (Hidden on mobile) -->
<div id="breadcrumb" class="hidden-xs">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li class="active">Daftar Customer</li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="form-box">
					<div class="text-center" style="margin-bottom: 25px;">
						<div style="width: 50px; height: 50px; background: var(--color-emerald-gradient); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: #fff; font-size: 22px; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(16,185,129,0.3);">
							<i class="fa fa-user-plus"></i>
						</div>
						<h2 style="font-size: 26px; font-weight: 800; margin-bottom: 6px;">Pendaftaran Akun Baru</h2>
						<p style="color: var(--color-text-muted);">Buat akun untuk memesan dan mengelola jadwal lapangan</p>
					</div>

					${alertHtml}

					<form action="daftar_act.php" method="post">
						<div class="form-group" style="margin-bottom: 16px;">
							<label for=""><i class="fa fa-user" style="color: var(--color-brand-primary);"></i> Nama Lengkap</label>
							<input type="text" class="form-control" required="required" name="nama" placeholder="Masukkan nama lengkap Anda...">
						</div>

						<div class="form-group" style="margin-bottom: 16px;">
							<label for=""><i class="fa fa-envelope" style="color: var(--color-brand-primary);"></i> Alamat Email</label>
							<input type="email" class="form-control" required="required" name="email" placeholder="Masukkan alamat email Anda...">
						</div>

						<div class="form-group" style="margin-bottom: 16px;">
							<label for=""><i class="fa fa-phone" style="color: var(--color-brand-primary);"></i> Nomor HP / WhatsApp</label>
							<input type="number" class="form-control" required="required" name="hp" placeholder="Contoh: 081234567890">
						</div>

						<div class="form-group" style="margin-bottom: 16px;">
							<label for=""><i class="fa fa-map-marker" style="color: var(--color-brand-primary);"></i> Alamat Lengkap</label>
							<input type="text" class="form-control" required="required" name="alamat" placeholder="Masukkan alamat Anda...">
						</div>

						<div class="form-group" style="margin-bottom: 24px;">
							<label for=""><i class="fa fa-key" style="color: var(--color-brand-primary);"></i> Password</label>
							<input type="password" class="form-control" required="required" name="password" placeholder="Buat password akun Anda...">
							<small class="text-muted">Password digunakan untuk masuk ke portal pelanggan SportKuy.</small>
						</div>

						<div class="form-group" style="margin-bottom: 0;">
							<button type="submit" class="primary-btn btn-block" style="padding: 12px; font-size: 16px;"><i class="fa fa-check-circle"></i> Daftar Sekarang</button>
							<div class="text-center" style="margin-top: 18px; color: var(--color-text-muted);">
								Sudah memiliki akun? <a href="masuk.php" style="color: var(--color-brand-primary); font-weight: 700;">Login Disini</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>`;
}

export function renderCheckoutPage(params: { customer: any; lapangan: any }): string {
  const { customer: i, lapangan: d } = params;
  return `<!-- BREADCRUMB -->
<div id="breadcrumb">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li class="active">Booking Lapangan</li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="order-summary clearfix">
					<div class="section-title">
						<h3 class="title">Booking Lapangan</h3>
					</div>

					<div class="row">
						<form method="post" action="checkout_act.php?id=${d.lapangan_id}">
							<div class="col-lg-6">
								<div class="row">
									<div class="col-lg-12">
										<br>
										<h4 class="text-center">INFORMASI PEMESAN</h4>

										<div class="form-group">
											<label>Nama</label>
											<input type="text" class="input" name="nama" value="${escapeHtml(i.customer_nama)}" required="required">
										</div>

										<div class="form-group">
											<label>Nomor HP</label>
											<input type="text" class="input" name="hp" value="${escapeHtml(i.customer_hp)}" required="required">
										</div>

										<div class="form-group">
											<label>Tanggal Main</label>
											<input type="input" class="form-control datepicker2" name="tglMain" required="required">
										</div>

										<div class="form-group">
  											<label for="inputGroupSelect01">Jam Mulai</label>
  											<select class="form-select" name="jamMulai">
    											<option selected value="08.00">08.00</option>
    											<option value="09.00">09.00</option>
    											<option value="10.00">10.00</option>
												<option value="11.00">11.00</option>
												<option value="12.00">12.00</option>
												<option value="13.00">13.00</option>
												<option value="14.00">14.00</option>
												<option value="15.00">15.00</option>
												<option value="16.00">16.00</option>
												<option value="17.00">17.00</option>
												<option value="18.00">18.00</option>
												<option value="19.00">19.00</option>
												<option value="20.00">20.00</option>
												<option value="21.00">21.00</option>
												<option value="22.00">22.00</option>
  											</select>
											<br>
										</div>

										<div class="form-group">
  											<label for="inputGroupSelect02">Jam Selesai</label>
  											<select class="form-select" name="jamSelesai">
    											<option selected value="09.00">09.00</option>
    											<option value="10.00">10.00</option>
												<option value="11.00">11.00</option>
												<option value="12.00">12.00</option>
												<option value="13.00">13.00</option>
												<option value="14.00">14.00</option>
												<option value="15.00">15.00</option>
												<option value="16.00">16.00</option>
												<option value="17.00">17.00</option>
												<option value="18.00">18.00</option>
												<option value="19.00">19.00</option>
												<option value="20.00">20.00</option>
												<option value="21.00">21.00</option>
												<option value="22.00">22.00</option>
												<option value="23.00">23.00</option>
  											</select>
											<br>
										</div>						

										<div class="form-group">
											<br>
											<label>Harga Perjam</label>
											<input type="text" class="input" name="harga" readonly value="${d.lapangan_harga}">
										</div>

										<br>
									</div>
								</div>

								<div class="row">
									<div class="col-lg-12">
										<div class="center">
											<input type="submit" class="primary-btn" value="Booking Lapangan">
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>`;
}

export function renderCustomerDashboardPage(params: { lang: LangCode; customer: any }): string {
  const { lang, customer: i } = params;
  const __t = createTranslator(lang);
  return `<!-- BREADCRUMB (Hidden on mobile) -->
<div id="breadcrumb" class="hidden-xs">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li class="active">Dashboard Customer</li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<div class="section">
	<div class="container">
		<div class="row">
			${renderCustomerSidebar(lang)}

			<div id="main" class="col-md-9">
				<h4>${__t('dashboard')}</h4>
				<div id="store">
					<div class="row">
						<div class="col-lg-12">
							<h5>${__t('customer_greeting')}</h5>
							<table class="table table-bordered">
								<tbody>
									<tr>
										<th width="20%">${__t('customer_name')}</th>	
										<td>${escapeHtml(i.customer_nama)}</td>
									</tr>
									<tr>
										<th width="20%">${__t('customer_email')}</th>	
										<td>${escapeHtml(i.customer_email)}</td>
									</tr>
									<tr>
										<th>${__t('customer_phone')}</th>	
										<td>${escapeHtml(i.customer_hp)}</td>
									</tr>
									<tr>
										<th>${__t('customer_address')}</th>	
										<td>${escapeHtml(i.customer_alamat)}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>`;
}

export function renderCustomerPesananPage(params: {
  lang: LangCode;
  invoices: Array<any>;
  alert?: string;
}): string {
  const { lang, invoices, alert } = params;
  const __t = createTranslator(lang);

  let alertHtml = '';
  if (alert === 'gagal') {
    alertHtml = `<div class='alert alert-danger'>Gambar gagal diupload!</div>`;
  } else if (alert === 'sukses') {
    alertHtml = `<div class='alert alert-success'>Pesanan berhasil dibuat, silahkan melakukan pembayaran!</div>`;
  } else if (alert === 'upload') {
    alertHtml = `<div class='alert alert-success'>Konfirmasi pembayaran berhasil tersimpan, silahkan menunggu konfirmasi dari admin!</div>`;
  }

  const rowsHtml = invoices
    .map((i) => {
      const st = Number(i.invoice_status);
      let statusBadge = '';
      if (st === 0) {
        statusBadge = `<span class='badge-status badge-warning'><i class='fa fa-clock-o'></i> Menunggu Pembayaran</span>`;
      } else if (st === 1) {
        statusBadge = `<span class='badge-status badge-warning'><i class='fa fa-hourglass-half'></i> Menunggu Konfirmasi</span>`;
      } else if (st === 2) {
        statusBadge = `<span class='badge-status badge-danger'><i class='fa fa-times-circle'></i> Ditolak</span>`;
      } else if (st === 3) {
        statusBadge = `<span class='badge-status badge-success'><i class='fa fa-check-circle'></i> Dikonfirmasi</span>`;
      } else if (st === 4) {
        statusBadge = `<span class='badge-status badge-success'><i class='fa fa-star'></i> Selesai</span>`;
      }

      const payBtn =
        st === 0 || st === 1
          ? `<a class='btn btn-sm btn-primary' href="customer_pembayaran.php?id=${i.invoice_id}"><i class="fa fa-money"></i> Konfirmasi Pembayaran</a>`
          : '';

      return `<tr>
												<td>${i.invoice_id}</td>
												<td>INVOICE-00${i.invoice_id}</td>
												<td>${dateToIndo(i.invoice_tanggal)}</td>
												<td>${dateToIndo(i.invoice_tgl_main)}</td>
												<td>${escapeHtml(i.invoice_nama)}</td>
												<td>Rp. ${numberFormat(i.invoice_total_bayar)} ,-</td>
												<td class="text-center">${statusBadge}</td>
												<td class="text-center">
													${payBtn}
													<a class='btn btn-sm btn-success' href="customer_invoice.php?id=${i.invoice_id}"><i class="fa fa-print"></i> Invoice</a>
												</td>
											</tr>`;
    })
    .join('\n');

  return `<!-- BREADCRUMB (Hidden on mobile) -->
<div id="breadcrumb" class="hidden-xs">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li class="active">Pesanan Customer</li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<div class="section">
	<div class="container">
		<div class="row">
			${renderCustomerSidebar(lang)}

			<div id="main" class="col-md-9">
				<h4>${__t('my_bookings')}</h4>
				<div id="store">
					<div class="row">
						<div class="col-lg-12">
							${alertHtml}
							<small class="text-muted">Semua data pesanan / invoice anda.</small>
							<br/><br/>

							<div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>NO</th>
											<th>${__t('invoice_no')}</th>
											<th>${__t('booking_date')}</th>
											<th>${__t('play_date')}</th>
											<th>${__t('customer_name')}</th>
											<th>${__t('total_amount')}</th>
											<th class="text-center">${__t('payment_status')}</th>
											<th class="text-center">${__t('action')}</th>
										</tr>
									</thead>
									<tbody>
										${rowsHtml}
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>`;
}

export function renderCustomerPembayaranPage(params: {
  lang: LangCode;
  invoiceId: number;
  invoice: any | null;
}): string {
  const { lang, invoiceId, invoice: i } = params;
  let rowHtml = '';
  if (i) {
    const st = Number(i.invoice_status);
    let label = '';
    if (st === 0) label = `<span class='label label-warning'>Menunggu Pembayaran</span>`;
    else if (st === 1) label = `<span class='label label-default'>Menunggu Konfirmasi</span>`;
    else if (st === 2) label = `<span class='label label-danger'>Ditolak</span>`;
    else if (st === 3) label = `<span class='label label-primary'>Dikonfirmasi & Sedang Diproses</span>`;
    else if (st === 4) label = `<span class='label label-warning'>Selesai</span>`;

    rowHtml = `<tr>
											<th width="20%">No.Invoice</th>	
											<td>INVOICE-00${i.invoice_id}</td>
										</tr>
										<tr>
											<th>Tanggal</th>	
											<td>${formatDMY(i.invoice_tanggal, '-')}</td>
										</tr>
										<tr>
											<th>Total Bayar</th>	
											<td>Rp. ${numberFormat(i.invoice_total_bayar)} ,-</td>
										</tr>
										<tr>
											<th>Status</th>	
											<td>${label}</td>
										</tr>`;
  }

  return `<!-- BREADCRUMB -->
<div id="breadcrumb">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li class="active">Konfirmasi Pembayaran</li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<div class="section">
	<div class="container">
		<div class="row">
			${renderCustomerSidebar(lang)}

			<div id="main" class="col-md-9">
				<h4>KONFIRMASI PEMBAYARAN</h4>
				<div id="store">
					<div class="row">
						<div class="col-lg-12">
							<table class="table table-bordered">
								<tbody>
									${rowHtml}
								</tbody>
							</table>

							<br/>
							<p>Silahkan Lakukan Pembayaran Ke Nomor Rekening Berikut :</p>
							<table class="table table-bordered">
								<tr>
									<th width="30%">Nomor Rekening</th>
									<td>123-456-7890</td>
								</tr>
								<tr>
									<th>Atas Nama</th>
									<td>Sport Center</td>
								</tr>
								<tr>
									<th>Bank</th>
									<td>BCA</td>
								</tr>
							</table>
							<br/>

							<form action="customer_pembayaran_act.php" method="post" enctype="multipart/form-data">
								<div class="form-group">
									<input type="hidden" name="id" value="${invoiceId}">
									<label>Upload Bukti Pembayaran</label>
									<input type="file" name="bukti" required="required">
									<small class="text-muted">File yang diperbolehkan hanya file gambar.</small>
								</div>
								<input type="submit" value="Upload Bukti Pembayaran" class="primary-btn">
							</form>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>`;
}

export function renderCustomerInvoicePage(params: {
  lang: LangCode;
  invoice: any | null;
  lapangan: any | null;
}): string {
  const { lang, invoice: i, lapangan: d } = params;

  let bodyHtml = '';
  if (i) {
    const startH = parseInt(String(i.invoice_jam_mulai).slice(0, 2), 10) || 0;
    const endH = parseInt(String(i.invoice_jam_selesai).slice(0, 2), 10) || 0;
    const durasi = endH > startH ? endH - startH : 1;
    const st = Number(i.invoice_status);
    let badgeHtml = '';
    if (st === 0) badgeHtml = `<span class='badge-status badge-warning'><i class='fa fa-clock-o'></i> Menunggu Pembayaran</span>`;
    else if (st === 1) badgeHtml = `<span class='badge-status badge-warning'><i class='fa fa-hourglass-half'></i> Menunggu Konfirmasi</span>`;
    else if (st === 2) badgeHtml = `<span class='badge-status badge-danger'><i class='fa fa-times-circle'></i> Ditolak</span>`;
    else if (st === 3) badgeHtml = `<span class='badge-status badge-success'><i class='fa fa-check-circle'></i> Dikonfirmasi</span>`;
    else if (st === 4) badgeHtml = `<span class='badge-status badge-success'><i class='fa fa-star'></i> Selesai</span>`;

    const imgSrc = d && d.lapangan_foto1 ? `gambar/lapangan/${escapeHtml(d.lapangan_foto1)}` : 'gambar/sistem/lapangan.png';

    bodyHtml = `<!-- ACTION BAR -->
				<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
					<a href="customer_pesanan.php" class="main-btn" style="display: inline-flex; align-items: center; gap: 8px;">
						<i class="fa fa-arrow-left"></i> Kembali ke Pesanan
					</a>
					<div style="display: flex; gap: 10px;">
						<a href="customer_invoice_cetak.php?id=${i.invoice_id}" target="_blank" class="primary-btn" style="display: inline-flex; align-items: center; gap: 8px;">
							<i class="fa fa-print"></i> Cetak Invoice
						</a>
					</div>
				</div>

				<!-- INVOICE CARD CONTAINER -->
				<div class="form-box" style="padding: 36px; background: #ffffff; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--color-border-light);">
					
					<div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px dashed #e2e8f0; padding-bottom: 24px; margin-bottom: 28px; flex-wrap: wrap; gap: 20px;">
						<div>
							<div class="logo-text" style="font-size: 28px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
								<span class="badge-icon" style="width: 42px; height: 42px; background: var(--color-brand-gradient); color: #fff; border-radius: var(--radius-md); display: inline-flex; align-items: center; justify-content: center; font-size: 20px; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);">
									<i class="fa fa-soccer-ball-o"></i>
								</span>
								SPORT<span style="color: #2563eb;">KUY</span>
							</div>
							<p style="color: var(--color-text-muted); font-size: 13px; margin: 0;">Platform Reservasi & Booking Lapangan Olahraga Online</p>
						</div>

						<div style="text-align: right;">
							<div style="font-size: 22px; font-weight: 800; color: var(--color-brand-primary); font-family: var(--font-heading);">
								INVOICE #INV-${strPadLeft(i.invoice_id, 5, '0')}
							</div>
							<p style="color: var(--color-text-muted); font-size: 13px; margin: 4px 0 10px 0;">
								Tgl Order: ${dateToIndo(i.invoice_tanggal)}
							</p>
							<div>${badgeHtml}</div>
						</div>
					</div>

					<div class="row" style="margin-bottom: 28px;">
						<div class="col-sm-6" style="margin-bottom: 15px;">
							<div style="background: #f8fafc; padding: 18px; border-radius: var(--radius-md); border: 1px solid #e2e8f0; height: 100%;">
								<h5 style="font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-muted); margin-bottom: 12px; font-weight: 700;">
									<i class="fa fa-user" style="color: var(--color-brand-primary);"></i> Informasi Pemesan
								</h5>
								<h4 style="font-size: 16px; font-weight: 800; margin: 0 0 6px 0; color: #0f172a;">
									${escapeHtml(i.invoice_nama)}
								</h4>
								<p style="margin: 0 0 4px 0; color: var(--color-text-main); font-size: 14px;">
									<i class="fa fa-phone" style="width: 18px; color: var(--color-brand-primary);"></i> ${escapeHtml(i.invoice_hp)}
								</p>
								<p style="margin: 0; color: var(--color-text-muted); font-size: 13px;">
									<i class="fa fa-map-marker" style="width: 18px; color: var(--color-brand-primary);"></i> Pelanggan Terdaftar
								</p>
							</div>
						</div>

						<div class="col-sm-6" style="margin-bottom: 15px;">
							<div style="background: #f8fafc; padding: 18px; border-radius: var(--radius-md); border: 1px solid #e2e8f0; height: 100%;">
								<h5 style="font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-muted); margin-bottom: 12px; font-weight: 700;">
									<i class="fa fa-calendar" style="color: var(--color-brand-primary);"></i> Jadwal Pertandingan
								</h5>
								<p style="margin: 0 0 6px 0; font-size: 15px; font-weight: 700; color: #0f172a;">
									<i class="fa fa-calendar-check-o" style="width: 18px; color: var(--color-brand-primary);"></i> ${dateToIndo(i.invoice_tgl_main)}
								</p>
								<p style="margin: 0 0 6px 0; font-size: 14px; font-weight: 600; color: var(--color-brand-primary);">
									<i class="fa fa-clock-o" style="width: 18px;"></i> Sesi Jam: ${escapeHtml(i.invoice_jam_mulai)} - ${escapeHtml(i.invoice_jam_selesai)} (${durasi} Jam)
								</p>
								<p style="margin: 0; color: var(--color-text-muted); font-size: 13px;">
									<i class="fa fa-tags" style="width: 18px; color: var(--color-brand-primary);"></i> Kategori: ${escapeHtml(d?.kategori_nama || 'Olahraga')}
								</p>
							</div>
						</div>
					</div>

					<div class="table-responsive" style="margin-bottom: 28px;">
						<table class="table" style="width: 100%;">
							<thead>
								<tr>
									<th width="5%" class="text-center">#</th>
									<th width="50%" colspan="2">Arena / Lapangan</th>
									<th width="15%" class="text-center">Harga / Jam</th>
									<th width="10%" class="text-center">Durasi</th>
									<th width="20%" class="text-right">Subtotal</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="text-center" style="font-weight: 700;">1</td>
									<td width="70" style="padding: 10px;">
										<img src="${imgSrc}" style="width: 60px; height: 45px; object-fit: cover; border-radius: 8px;">
									</td>
									<td>
										<div style="font-weight: 800; font-size: 15px; color: #0f172a;">${escapeHtml(d?.lapangan_nama || 'Lapangan SportKuy')}</div>
										<small style="color: var(--color-text-muted);">${dateToIndo(i.invoice_tgl_main)} | Jam ${escapeHtml(i.invoice_jam_mulai)} - ${escapeHtml(i.invoice_jam_selesai)}</small>
									</td>
									<td class="text-center" style="font-weight: 600;">Rp ${numberFormat(i.invoice_harga)}</td>
									<td class="text-center" style="font-weight: 700;">${durasi} Jam</td>
									<td class="text-right" style="font-weight: 800; color: #0f172a;">Rp ${numberFormat(i.invoice_total_bayar)}</td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="row" style="align-items: center;">
						<div class="col-sm-6" style="margin-bottom: 15px;">
							<div style="background: #f1f5f9; padding: 14px 18px; border-radius: var(--radius-md); font-size: 13px; color: var(--color-text-muted);">
								<i class="fa fa-info-circle" style="color: var(--color-brand-primary);"></i> Tunjukkan invoice ini kepada petugas lokasi saat memasuki arena pertandingan.
							</div>
						</div>
						<div class="col-sm-6 text-right">
							<div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; padding: 18px 24px; border-radius: var(--radius-md); display: inline-block; min-width: 260px; text-align: right; box-shadow: 0 4px 14px rgba(15,23,42,0.15);">
								<div style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 4px;">Total Tagihan</div>
								<div style="font-size: 26px; font-weight: 800; color: #38bdf8; font-family: var(--font-heading);">
									Rp ${numberFormat(i.invoice_total_bayar)}
								</div>
							</div>
						</div>
					</div>

				</div>`;
  } else {
    bodyHtml = `<div class="alert alert-danger text-center" style="padding: 20px; border-radius: var(--radius-md);">
						<i class="fa fa-exclamation-triangle fa-2x"></i><br><br>
						Data Invoice tidak ditemukan atau Anda tidak memiliki akses.
					</div>`;
  }

  return `<!-- BREADCRUMB -->
<div id="breadcrumb">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li><a href="customer_pesanan.php">Pesanan Saya</a></li>
			<li class="active">Invoice</li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<div class="section">
	<div class="container">
		<div class="row">
			${renderCustomerSidebar(lang)}
			<div id="main" class="col-md-9">
				${bodyHtml}
			</div>
		</div>
	</div>
</div>`;
}

export function renderInvoiceCetakPage(params: {
  invoice: any | null;
  lapangan: any | null;
  isAdmin?: boolean;
}): string {
  const { invoice: i, lapangan: d, isAdmin } = params;
  const cssPrefix = isAdmin ? '../' : '';
  const invNum = i ? strPadLeft(i.invoice_id, 5, '0') : '';

  let cardContent = '';
  if (i) {
    const startH = parseInt(String(i.invoice_jam_mulai).slice(0, 2), 10) || 0;
    const endH = parseInt(String(i.invoice_jam_selesai).slice(0, 2), 10) || 0;
    const durasi = endH > startH ? endH - startH : 1;
    const st = Number(i.invoice_status);
    let badgeHtml = '';
    if (st === 0) badgeHtml = `<span class='badge badge-warning'><i class='fa fa-clock-o'></i> Menunggu Pembayaran</span>`;
    else if (st === 1) badgeHtml = `<span class='badge badge-info'><i class='fa fa-hourglass-half'></i> Menunggu Konfirmasi</span>`;
    else if (st === 2) badgeHtml = `<span class='badge badge-danger'><i class='fa fa-times-circle'></i> Ditolak</span>`;
    else if (st === 3) badgeHtml = `<span class='badge badge-success'><i class='fa fa-check-circle'></i> Dikonfirmasi</span>`;
    else if (st === 4) badgeHtml = `<span class='badge badge-success'><i class='fa fa-star'></i> Selesai</span>`;

    cardContent = `<div class="invoice-header">
			<div>
				<div class="logo-title">
					<span class="logo-icon"><i class="fa ${isAdmin ? 'fa-trophy' : 'fa-soccer-ball-o'}"></i></span>
					SPORT<span style="color: #2563eb;">KUY</span>${isAdmin ? ' ADMIN' : ''}
				</div>
				<p style="color: #64748b; font-size: 13px; margin: 4px 0 0 0;">${isAdmin ? 'Bukti Reservasi & Transaksi Resmi System' : 'Bukti Pembayaran & Booking Resmi'}</p>
			</div>

			<div class="invoice-num">
				INVOICE #INV-${invNum}
				<div style="font-size: 13px; color: #64748b; font-weight: 500; margin-top: 4px;">
					Tanggal Order: ${dateToIndo(i.invoice_tanggal)}
				</div>
				<div style="margin-top: 8px;">${badgeHtml}</div>
			</div>
		</div>

		<div class="grid-2">
			<div class="info-box">
				<h5><i class="fa fa-user" style="color: #2563eb;"></i> Data Pemesan</h5>
				<h4>${escapeHtml(i.invoice_nama)}</h4>
				<p><i class="fa fa-phone" style="color: #2563eb; width: 16px;"></i> ${escapeHtml(i.invoice_hp)}</p>
				<p><i class="fa fa-map-marker" style="color: #2563eb; width: 16px;"></i> Pelanggan Terdaftar</p>
			</div>

			<div class="info-box">
				<h5><i class="fa fa-calendar" style="color: #2563eb;"></i> Detail Jadwal Pertandingan</h5>
				<p style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">
					<i class="fa fa-calendar-check-o" style="color: #2563eb; width: 16px;"></i> ${dateToIndo(i.invoice_tgl_main)}
				</p>
				<p style="font-size: 14px; font-weight: 600; color: #2563eb; margin-bottom: 6px;">
					<i class="fa fa-clock-o" style="width: 16px;"></i> Jam Sesi: ${escapeHtml(i.invoice_jam_mulai)} - ${escapeHtml(i.invoice_jam_selesai)} (${durasi} Jam)
				</p>
				<p style="color: #64748b; font-size: 13px;">
					<i class="fa fa-tags" style="color: #2563eb; width: 16px;"></i> Kategori: ${escapeHtml(d?.kategori_nama || 'Olahraga')}
				</p>
			</div>
		</div>

		<table class="table">
			<thead>
				<tr>
					<th width="5%" style="text-align: center;">#</th>
					<th width="55%">Arena / Lapangan Olahraga</th>
					<th width="15%" style="text-align: center;">Harga / Jam</th>
					<th width="10%" style="text-align: center;">Durasi</th>
					<th width="15%" style="text-align: right;">Total</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="text-align: center; font-weight: 700;">1</td>
					<td>
						<div style="font-weight: 800; font-size: 15px; color: #0f172a;">${escapeHtml(d?.lapangan_nama || 'Lapangan SportKuy')}</div>
						<div style="font-size: 12px; color: #64748b;">${dateToIndo(i.invoice_tgl_main)} | Jam ${escapeHtml(i.invoice_jam_mulai)} - ${escapeHtml(i.invoice_jam_selesai)}</div>
					</td>
					<td style="text-align: center; font-weight: 600;">Rp ${numberFormat(i.invoice_harga)}</td>
					<td style="text-align: center; font-weight: 700;">${durasi} Jam</td>
					<td style="text-align: right; font-weight: 800; color: #0f172a;">Rp ${numberFormat(i.invoice_total_bayar)}</td>
				</tr>
			</tbody>
		</table>

		<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 10px;">
			<div style="font-size: 12px; color: #64748b; max-width: 400px;">
				Catatan: ${isAdmin ? 'Dokumen ini dicetak dari Admin Portal <strong>SportKuy</strong> sebagai arsip transaksi resmi.' : 'Bukti transaksi ini sah dan dikeluarkan secara otomatis oleh sistem reservasi online <strong>SportKuy</strong>.'}
			</div>
			<div class="total-box">
				<div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 2px;">Total Tagihan</div>
				<div style="font-size: 24px; font-weight: 800; color: #38bdf8; font-family: 'Outfit', sans-serif;">
					Rp ${numberFormat(i.invoice_total_bayar)}
				</div>
			</div>
		</div>`;
  } else {
    cardContent = `<div style="text-align: center; padding: 40px; color: #ef4444; font-weight: 700;">Data invoice tidak ditemukan!</div>`;
  }

  return `<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<title>Cetak Invoice ${isAdmin ? 'Admin ' : ''}#INV-${invNum} - SportKuy</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
	<link rel="stylesheet" href="${cssPrefix}frontend/css/font-awesome.min.css">
	<style>
		* { box-sizing: border-box; }
		body {
			font-family: 'Plus Jakarta Sans', sans-serif;
			color: #1e293b;
			background: #f8fafc;
			margin: 0;
			padding: 40px 20px;
			-webkit-print-color-adjust: exact !important;
			print-color-adjust: exact !important;
		}
		.invoice-card {
			max-width: 800px;
			margin: 0 auto;
			background: #ffffff;
			border-radius: 16px;
			padding: 40px;
			box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
			border: 1px solid #e2e8f0;
		}
		.invoice-header {
			display: flex;
			justify-content: space-between;
			align-items: flex-start;
			border-bottom: 2px dashed #cbd5e1;
			padding-bottom: 24px;
			margin-bottom: 28px;
		}
		.logo-title {
			font-family: 'Outfit', sans-serif;
			font-size: 26px;
			font-weight: 800;
			color: #0f172a;
			display: flex;
			align-items: center;
			gap: 10px;
		}
		.logo-icon {
			width: 38px;
			height: 38px;
			background: linear-gradient(135deg, #2563eb, #1d4ed8);
			color: #fff;
			border-radius: 10px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			font-size: 18px;
		}
		.invoice-num {
			font-family: 'Outfit', sans-serif;
			font-size: 22px;
			font-weight: 800;
			color: #2563eb;
			text-align: right;
		}
		.grid-2 {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 20px;
			margin-bottom: 28px;
		}
		.info-box {
			background: #f8fafc;
			padding: 18px;
			border-radius: 12px;
			border: 1px solid #e2e8f0;
		}
		.info-box h5 {
			margin: 0 0 10px 0;
			font-size: 12px;
			text-transform: uppercase;
			letter-spacing: 0.05em;
			color: #64748b;
			font-weight: 700;
		}
		.info-box h4 {
			margin: 0 0 6px 0;
			font-size: 16px;
			font-weight: 800;
			color: #0f172a;
		}
		.info-box p {
			margin: 0 0 4px 0;
			font-size: 13px;
			color: #334155;
		}
		.table {
			width: 100%;
			border-collapse: separate;
			border-spacing: 0;
			border-radius: 12px;
			overflow: hidden;
			border: 1px solid #e2e8f0;
			margin-bottom: 24px;
		}
		.table th {
			background: #f1f5f9;
			color: #0f172a;
			font-weight: 700;
			font-size: 13px;
			padding: 12px 16px;
			text-align: left;
			border-bottom: 2px solid #e2e8f0;
		}
		.table td {
			padding: 14px 16px;
			font-size: 14px;
			border-bottom: 1px solid #f1f5f9;
		}
		.badge {
			padding: 6px 14px;
			border-radius: 9999px;
			font-weight: 700;
			font-size: 12px;
			display: inline-block;
		}
		.badge-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
		.badge-warning { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
		.badge-danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
		.badge-info { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
		.total-box {
			background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
			color: #ffffff;
			padding: 16px 24px;
			border-radius: 12px;
			display: inline-block;
			text-align: right;
			float: right;
		}
		.no-print-btn {
			background: #2563eb;
			color: #ffffff;
			border: none;
			padding: 12px 24px;
			border-radius: 10px;
			font-weight: 700;
			cursor: pointer;
			margin-bottom: 20px;
			display: inline-flex;
			align-items: center;
			gap: 8px;
			box-shadow: 0 4px 12px rgba(37,99,235,0.3);
			text-decoration: none;
		}
		@media print {
			body { background: #ffffff; padding: 0; }
			.invoice-card { box-shadow: none; border: none; padding: 0; }
			.no-print { display: none !important; }
		}
	</style>
</head>
<body>
	<div style="max-width: 800px; margin: 0 auto 10px auto; text-align: right;" class="no-print">
		<button onclick="window.print()" class="no-print-btn"><i class="fa fa-print"></i> Cetak Invoice Sekarang</button>
	</div>

	<div class="invoice-card">
		${cardContent}
	</div>

	<script>
		window.onload = function() {
			window.print();
		};
	</script>
</body>
</html>`;
}

export function renderCustomerPasswordPage(params: { lang: LangCode; alert?: string }): string {
  const { lang, alert } = params;
  const alertHtml =
    alert === 'sukses'
      ? `<div class='alert alert-success'>Password anda berhasil diganti!</div>`
      : '';

  return `<!-- BREADCRUMB -->
<div id="breadcrumb">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li class="active">Ganti Password Customer</li>
		</ul>
	</div>
</div>
<!-- /BREADCRUMB -->

<div class="section">
	<div class="container">
		<div class="row">
			${renderCustomerSidebar(lang)}

			<div id="main" class="col-md-9">
				<h4>GANTI PASSWORD</h4>
				<div id="store">
					<div class="row">
						<div class="col-lg-12">
							${alertHtml}
							<form action="customer_password_act.php" method="post">
								<div class="form-group">
									<label for="">Masukkan Password Baru</label>
									<input type="password" class="input" required="required" name="password" placeholder="Masukkan password .." min="5">
								</div>

								<div class="form-group">
									<input type="submit" class="primary-btn" value="Ganti Password">
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>`;
}
