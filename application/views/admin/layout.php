<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - BannerPosko</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body>

<?php $this->load->view('admin/sidebar'); ?>

<!-- ===== MAIN ===== -->
<div class="main" id="main">

    <div class="navbar">
        <button class="hamburger" onclick="toggleSidebar()">☰</button>
        <div class="navbar-breadcrumb">BannerPosko > <span>Dashboard</span></div>
        <div class="navbar-right">
            <a href="<?= base_url('Auth/logout') ?>" class="navbar-logout"> Logout</a>
            <?php $foto_nav = $this->session->userdata('foto_profil'); ?>
            <?php if ($foto_nav): ?>
                <img src="<?= base_url('uploads/profil/' . $foto_nav) ?>"
                    style="width:34px;height:34px;border-radius:50%;object-fit:cover;">
            <?php else: ?>
                <div class="navbar-ava"><?= strtoupper(substr($this->session->userdata('username'), 0, 1)) ?></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="content">
        <div class="page-title">Dashboard</div>
        <div class="page-sub">Selamat datang kembali, <?= $this->session->userdata('username') ?></div>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success">✓ <?= $this->session->flashdata('success') ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-error">✗ <?= $this->session->flashdata('error') ?></div>
        <?php endif; ?>

        <div class="stats-grid">
            <div class="stat-card green">
                <div class="stat-label">Banner Aktif</div>
                <div class="stat-value"><?= $banner_aktif ?></div>
                <div class="stat-desc">Banner sedang ditampilkan</div>
            </div>
            <div class="stat-card red">
                <div class="stat-label">Banner Nonaktif</div>
                <div class="stat-value"><?= $banner_nonaktif ?></div>
                <div class="stat-desc">Banner tidak ditampilkan</div>
            </div>
            <div class="stat-card blue">
                <div class="stat-label">Terakhir Diupdate</div>
                <div class="stat-value"><?= $terakhir_update ? date('d M Y', strtotime($terakhir_update)) : '—' ?></div>
                <div class="stat-desc"><?= $terakhir_update ? date('H:i', strtotime($terakhir_update)) . ' WIB' : 'Belum ada perubahan' ?></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Daftar Banner</div>
                    <div class="card-sub">Semua banner yang tersimpan</div>
                </div>
                <button class="btn-add" onclick="openModal('modalUpload')">+ Tambah Banner</button>
            </div>

            <?php if (empty($banners)): ?>
                <div class="empty-state">
                    <div class="empty-icon"></div>
                    <div>Belum ada banner. Klik "Tambah Banner" untuk mulai.</div>
                </div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Status</th>
                        <th>Jadwal Mulai</th>
                        <th>Jadwal Selesai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($banners as $i => $b): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <?php if ($b->tipe === 'video'): ?>
                                <video src="<?= base_url('uploads/' . $b->gambar) ?>" style="height:44px; border-radius:6px; object-fit:cover;" muted></video>
                            <?php elseif ($b->tipe === 'url'): ?>
                                <img src="<?= $b->url ?>" style="height:44px; border-radius:6px; object-fit:cover;">
                            <?php else: ?>
                                <img src="<?= base_url('uploads/' . $b->gambar) ?>" style="height:44px; border-radius:6px; object-fit:cover;">
                            <?php endif; ?>
                        </td>
                        <td><span class="badge <?= $b->status ?>">● <?= $b->status === 'aktif' ? 'Aktif' : 'Nonaktif' ?></span></td>
                        <td><?= $b->jadwal_mulai ? date('d M Y H:i', strtotime($b->jadwal_mulai)) : '—' ?></td>
                        <td><?= $b->jadwal_selesai ? date('d M Y H:i', strtotime($b->jadwal_selesai)) : '—' ?></td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-tbl" onclick="openJadwal(<?= $b->id ?>, '<?= $b->jadwal_mulai ?>', '<?= $b->jadwal_selesai ?>')">Jadwal</button>
                                <?php if ($b->status == 'aktif'): ?>
                                    <a href="<?= base_url('preview/' . $b->id) ?>" target="_blank" class="btn-tbl">Lihat</a>
                                <?php else: ?>
                                    <button class="btn-tbl" onclick="alert('Aktifkan banner ini terlebih dahulu!')">Lihat</button>
                                <?php endif; ?>
                                <a href="<?= base_url('Admin/hapus/' . $b->id) ?>" class="btn-tbl danger" onclick="return confirm('Hapus banner ini?')">Hapus</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Tambah Banner Baru -->
<div class="modal-overlay" id="modalUpload">
    <div class="modal">
        <div class="modal-head">
            <h3>Tambah Banner</h3>
            <button class="modal-close" onclick="closeModal('modalUpload')">✕</button>
        </div>
        <form action="<?= base_url('Admin/upload_gambar') ?>" method="POST" enctype="multipart/form-data">

            <!-- Pilih Tipe -->
            <div class="form-group">
                <label class="form-label">Tipe Konten</label>
                <div style="display:flex; gap:8px;">
                    <label style="flex:1; cursor:pointer;">
                        <input type="radio" name="tipe" value="gambar" checked onchange="switchTipe('gambar')" style="display:none;">
                        <div class="tipe-btn active" id="tipe-gambar">Gambar</div>
                    </label>
                    <label style="flex:1; cursor:pointer;">
                        <input type="radio" name="tipe" value="url" onchange="switchTipe('url')" style="display:none;">
                        <div class="tipe-btn" id="tipe-url"> URL</div>
                    </label>
                    <label style="flex:1; cursor:pointer;">
                        <input type="radio" name="tipe" value="video" onchange="switchTipe('video')" style="display:none;">
                        <div class="tipe-btn" id="tipe-video"> Video</div>
                    </label>
                </div>
            </div>

            <!-- Upload Gambar -->
            <div class="form-group" id="input-gambar">
                <label class="form-label">Pilih Gambar</label>
                <div class="upload-area" onclick="document.getElementById('fileInput').click()">
                    <div class="upload-icon"></div>
                    <div class="upload-text">Klik untuk pilih gambar<br><span>JPG, PNG, GIF — Maks 10MB</span></div>
                    <div id="fileName" style="margin-top:8px; font-size:12px; color:var(--blue);"></div>
                </div>
                <input type="file" id="fileInput" name="gambar" accept="image/*" style="display:none" onchange="showFileName(this)">
            </div>

            <!-- Input URL -->
            <div class="form-group" id="input-url" style="display:none;">
                <label class="form-label">URL Gambar</label>
                <input type="text" name="url_input" class="form-input" placeholder="https://example.com/gambar.jpg">
                <div style="font-size:11px; color:var(--text-muted); margin-top:4px;">Masukkan URL gambar yang akan ditampilkan</div>
            </div>

            <!-- Upload Video -->
            <div class="form-group" id="input-video" style="display:none;">
                <label class="form-label">Pilih Video</label>
                <div class="upload-area" onclick="document.getElementById('videoInput').click()">
                    <div class="upload-icon"></div>
                    <div class="upload-text">Klik untuk pilih video<br><span>MP4, WEBM — Maks 100MB</span></div>
                    <div id="videoName" style="margin-top:8px; font-size:12px; color:var(--blue);"></div>
                </div>
                <input type="file" id="videoInput" name="gambar" accept="video/*" style="display:none" onchange="showVideoName(this)">
            </div>

            <div class="form-group">
                <label class="form-label">Jadwal Mulai (opsional)</label>
                <input type="datetime-local" name="jadwal_mulai" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Jadwal Selesai (opsional)</label>
                <input type="datetime-local" name="jadwal_selesai" class="form-input">
            </div>
            <button type="submit" class="btn-submit">Simpan Banner</button>
        </form>
    </div>
</div>

<!-- Modal Update Media Banner -->
<div class="modal-overlay" id="modalUpdateGambar">
    <div class="modal">
        <div class="modal-head">
            <h3>Update Media Banner</h3>
            <button class="modal-close" onclick="closeModal('modalUpdateGambar')">✕</button>
        </div>
        <?php if (empty($banners)): ?>
            <p style="color:var(--text-muted); font-size:13px;">Belum ada banner.</p>
        <?php else: ?>
        <form id="formUpdateGambar" action="" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label class="form-label">1. Pilih Banner</label>
                <select class="form-select" name="banner_id" id="selectBannerGambar" onchange="previewBannerLama(this)" required>
                    <option value="">— Pilih Banner —</option>
                    <?php foreach ($banners as $b): ?>
                        <option value="<?= $b->id ?>"
                            data-tipe="<?= $b->tipe ?>"
                            data-src="<?= $b->tipe === 'url' ? $b->url : base_url('uploads/' . $b->gambar) ?>">
                            Banner #<?= $b->id ?> — <?= ucfirst($b->tipe) ?> — <?= $b->status === 'aktif' ? 'Aktif' : 'Nonaktif' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Preview media lama -->
            <div id="previewLama" style="display:none; margin-bottom:16px; background:#f9fafb; border-radius:8px; padding:12px; text-align:center;">
                <div style="font-size:11px; color:var(--text-muted); margin-bottom:8px;">Media saat ini:</div>
                <img id="previewLamaImg" src="" style="max-height:80px; border-radius:6px; display:none;">
                <video id="previewLamaVideo" src="" style="max-height:80px; border-radius:6px; display:none;" muted></video>
                <div id="previewLamaUrl" style="font-size:12px; color:var(--blue); display:none; word-break:break-all;"></div>
            </div>

            <!-- Input otomatis sesuai tipe -->
            <input type="hidden" name="tipe" id="hiddenTipe" value="">

            <div class="form-group" id="uinput-gambar" style="display:none;">
                <label class="form-label">2. Upload Gambar Baru</label>
                <div class="upload-area" onclick="document.getElementById('fileInputUpdate').click()">
                    <div class="upload-icon"></div>
                    <div class="upload-text">Klik untuk pilih gambar<br><span>JPG, PNG, GIF — Maks 10MB</span></div>
                    <div id="fileNameUpdate" style="margin-top:8px; font-size:12px; color:var(--blue);"></div>
                </div>
                <input type="file" id="fileInputUpdate" name="gambar" accept="image/*" style="display:none" onchange="document.getElementById('fileNameUpdate').textContent = '📁 ' + this.files[0].name">
            </div>

            <div class="form-group" id="uinput-url" style="display:none;">
                <label class="form-label">2. URL Gambar Baru</label>
                <input type="text" name="url_input" class="form-input" placeholder="https://example.com/gambar.jpg">
            </div>

            <div class="form-group" id="uinput-video" style="display:none;">
                <label class="form-label">2. Upload Video Baru</label>
                <div class="upload-area" onclick="document.getElementById('videoInputUpdate').click()">
                    <div class="upload-icon"></div>
                    <div class="upload-text">Klik untuk pilih video<br><span>MP4, WEBM — Maks 100MB</span></div>
                    <div id="videoNameUpdate" style="margin-top:8px; font-size:12px; color:var(--blue);"></div>
                </div>
                <input type="file" id="videoInputUpdate" name="gambar" accept="video/*" style="display:none" onchange="document.getElementById('videoNameUpdate').textContent = '🎬 ' + this.files[0].name">
            </div>

            <button type="submit" class="btn-submit" id="btnSimpanMedia" style="display:none;">Simpan Media Baru</button>
        </form>
        <?php endif; ?>
    </div>
</div>
<!-- Modal Update Penjadwalan -->
<div class="modal-overlay" id="modalJadwalBaru">
    <div class="modal">
        <div class="modal-head">
            <h3>Update Penjadwalan</h3>
            <button class="modal-close" onclick="closeModal('modalJadwalBaru')">✕</button>
        </div>
        <?php if (empty($banners)): ?>
            <p style="color:var(--text-muted); font-size:13px;">Belum ada banner. Upload gambar terlebih dahulu.</p>
        <?php else: ?>
        <form id="formJadwalBaru" action="" method="POST">
            <div class="form-group">
                <label class="form-label">Pilih Banner</label>
                <select class="form-select" onchange="pilihBanner(this)">
                    <option value="">— Pilih Banner —</option>
                    <?php foreach ($banners as $b): ?>
                        <option value="<?= $b->id ?>" data-mulai="<?= $b->jadwal_mulai ?>" data-selesai="<?= $b->jadwal_selesai ?>">
                            Banner #<?= $b->id ?> (<?= $b->status ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Jadwal Mulai</label>
                <input type="datetime-local" name="jadwal_mulai" id="jadwalMulai2" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Jadwal Selesai</label>
                <input type="datetime-local" name="jadwal_selesai" id="jadwalSelesai2" class="form-input">
            </div>
            <button type="submit" class="btn-submit">Simpan Penjadwalan</button>
        </form>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Edit Jadwal -->
<div class="modal-overlay" id="modalJadwal">
    <div class="modal">
        <div class="modal-head">
            <h3>Edit Jadwal Banner</h3>
            <button class="modal-close" onclick="closeModal('modalJadwal')">✕</button>
        </div>
        <form id="formJadwal" action="" method="POST">
            <div class="form-group">
                <label class="form-label">Jadwal Mulai</label>
                <input type="datetime-local" name="jadwal_mulai" id="jadwalMulai" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Jadwal Selesai</label>
                <input type="datetime-local" name="jadwal_selesai" id="jadwalSelesai" class="form-input">
            </div>
            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </form>
    </div>
</div>

<!-- Modal Running Text -->
<div class="modal-overlay" id="modalRunningText">
    <div class="modal">
        <div class="modal-head">
            <h3> Update Running Text</h3>
            <button class="modal-close" onclick="closeModal('modalRunningText')">✕</button>
        </div>
        <form action="<?= base_url('Admin/simpan_running_text') ?>" method="POST">
            <div class="form-group">
                <label class="form-label">Teks Berjalan</label>
                <textarea name="running_text" class="form-input" rows="4" style="resize:vertical;"><?= isset($running_text) ? $running_text : '' ?></textarea>
                <div style="font-size:11px; color:var(--text-muted); margin-top:4px;">Gunakan " | " sebagai pemisah antar teks</div>
            </div>
            <button type="submit" class="btn-submit">Simpan Running Text</button>
        </form>
    </div>
</div>

<script>const BASE_URL = '<?= base_url() ?>';</script>
<script src="<?= base_url('assets/js/admin.js') ?>"></script>
</body>
</html>