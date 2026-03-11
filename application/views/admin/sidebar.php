<!-- ===== SIDEBAR ===== -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"></div>
        <span class="brand-name">BannerPosko</span>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Menu</div>
        <a class="nav-link <?= (uri_string() == 'Admin' || uri_string() == 'Admin/index') ? 'active' : '' ?>"
           href="<?= base_url('Admin') ?>">
            <span class="icon"></span> Dashboard
        </a>

        <!-- Tampilan -->
        <div class="nav-label" style="margin-top:10px;">Tampilan</div>
        <div class="nav-accordion-header" onclick="toggleAccordion('displaySubmenu', 'arrowDisplay')">
            <div class="left"><span class="icon"></span> Display Settings</div>
            <span class="accordion-arrow" id="arrowDisplay">›</span>
        </div>
        <div class="nav-submenu" id="displaySubmenu">
            <a class="nav-link" href="#" onclick="openModal('modalUpdateGambar'); return false;">
                <span class="icon"></span> Update Media
            </a>
            <a class="nav-link" href="#" onclick="openModal('modalDisplayRT'); return false;">
                <span class="icon"></span> Running Text
            </a>
            <a class="nav-link" href="#" onclick="openModal('modalDisplayDT'); return false;">
                <span class="icon"></span> Date & Time
            </a>
            <a class="nav-link" href="#" onclick="openModal('modalDisplayBar'); return false;">
                <span class="icon"></span> Bottom Bar
            </a>
            <a class="nav-link" href="#" onclick="openModal('modalDisplaySlider'); return false;">
                <span class="icon"></span> Slider Interval
            </a>
        </div>

        <!-- Lainnya -->
        <div class="nav-label" style="margin-top:10px;">Lainnya</div>
        <a class="nav-link <?= (uri_string() == 'Admin/pengaturan') ? 'active' : '' ?>"
           href="<?= base_url('Admin/pengaturan') ?>">
            <span class="icon"></span> Profil Settings
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-box">
            <?php $foto = $this->session->userdata('foto_profil'); ?>
            <?php if ($foto): ?>
                <img src="<?= base_url('uploads/profil/' . $foto) ?>"
                     style="width:34px;height:34px;border-radius:50%;object-fit:cover;flex-shrink:0;">
            <?php else: ?>
                <div class="user-ava"><?= strtoupper(substr($this->session->userdata('username'), 0, 1)) ?></div>
            <?php endif; ?>
            <div>
                <div class="user-name">
                    <?= $this->session->userdata('nama_tampilan') ?: $this->session->userdata('username') ?>
                </div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
    </div>
</div>