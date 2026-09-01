<?php
use yii\helpers\Html;

$username = !Yii::$app->user->isGuest ? Yii::$app->user->identity->username : 'Guest';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

/* ── Pinterest sidebar tokens ── */
.pin-sidebar {
  --pin-primary:        #e60023;
  --pin-ink:            #000000;
  --pin-body:           #33332e;
  --pin-mute:           #62625b;
  --pin-ash:            #91918c;
  --pin-hairline:       #dadad3;
  --pin-hairline-soft:  #e5e5e0;
  --pin-canvas:         #ffffff;
  --pin-surface-soft:   #fbfbf9;
  --pin-surface-card:   #f6f6f3;
  --pin-r-md:           16px;
  --pin-r-full:         9999px;
  --pin-font: 'Inter', -apple-system, system-ui, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

/* Override AdminLTE skin-red sidebar */
.skin-red .pin-sidebar.main-sidebar,
.pin-sidebar.main-sidebar {
  background: var(--pin-canvas) !important;
  border-right: 1px solid var(--pin-hairline);
  font-family: var(--pin-font);
  box-shadow: none !important;
}

.pin-sidebar .sidebar {
  padding: 0;
  background: var(--pin-canvas) !important;
}

/* ── User panel ── */
.pin-sidebar .pin-user-panel {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 20px 16px 16px;
  border-bottom: 1px solid var(--pin-hairline-soft);
  margin: 0;
  overflow: hidden;
}
.pin-sidebar .pin-user-panel::before,
.pin-sidebar .pin-user-panel::after { display: none; }

.pin-sidebar .pin-user-avatar {
  width: 40px;
  height: 40px;
  border-radius: var(--pin-r-full);
  object-fit: cover;
  flex-shrink: 0;
  border: 2px solid var(--pin-hairline-soft);
}
.pin-sidebar .pin-user-info { flex: 1; min-width: 0; }
.pin-sidebar .pin-user-name {
  font-size: 14px;
  font-weight: 600;
  color: var(--pin-ink);
  margin: 0 0 2px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.3;
}
.pin-sidebar .pin-user-status {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  font-size: 12px;
  font-weight: 500;
  color: var(--pin-mute);
  text-decoration: none;
}
.pin-sidebar .pin-user-status:hover { color: var(--pin-body); text-decoration: none; }
.pin-sidebar .pin-status-dot {
  width: 7px;
  height: 7px;
  border-radius: var(--pin-r-full);
  background: #22c55e;
  flex-shrink: 0;
}

/* ── Menu list ── */
.pin-sidebar .pin-sidebar-menu {
  list-style: none;
  padding: 12px 10px 20px !important;
  margin: 0;
}
.pin-sidebar .pin-sidebar-menu > li {
  margin: 0 0 2px;
}
.pin-sidebar .pin-sidebar-menu > li.header {
  padding: 16px 12px 6px;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .6px;
  text-transform: uppercase;
  color: var(--pin-ash) !important;
  background: transparent !important;
  border: none;
  margin: 0;
}
.pin-sidebar .pin-sidebar-menu > li > a {
  display: flex !important;
  align-items: center;
  gap: 10px;
  padding: 10px 12px !important;
  border-radius: var(--pin-r-md) !important;
  font-size: 14px !important;
  font-weight: 500 !important;
  color: var(--pin-body) !important;
  background: transparent !important;
  border-left: none !important;
  transition: background .12s, color .12s;
  line-height: 1.3;
}
.pin-sidebar .pin-sidebar-menu > li > a:hover,
.pin-sidebar .pin-sidebar-menu > li > a:focus {
  background: var(--pin-surface-card) !important;
  color: var(--pin-ink) !important;
}
.pin-sidebar .pin-sidebar-menu > li.active > a {
  background: var(--pin-ink) !important;
  color: #fff !important;
  font-weight: 600 !important;
}
.pin-sidebar .pin-sidebar-menu > li.active > a .pin-menu-icon {
  color: #fff !important;
}

/* Icon */
.pin-sidebar .pin-sidebar-menu > li > a > .fa,
.pin-sidebar .pin-sidebar-menu > li > a > .glyphicon,
.pin-sidebar .pin-sidebar-menu > li > a > .ion {
  width: 20px;
  text-align: center;
  font-size: 14px;
  color: var(--pin-mute);
  flex-shrink: 0;
  transition: color .12s;
}
.pin-sidebar .pin-sidebar-menu > li > a:hover > .fa,
.pin-sidebar .pin-sidebar-menu > li > a:hover > .glyphicon,
.pin-sidebar .pin-sidebar-menu > li > a:hover > .ion {
  color: var(--pin-ink);
}
.pin-sidebar .pin-sidebar-menu > li.active > a > .fa,
.pin-sidebar .pin-sidebar-menu > li.active > a > .glyphicon,
.pin-sidebar .pin-sidebar-menu > li.active > a > .ion {
  color: #fff !important;
}

/* Label text */
.pin-sidebar .pin-sidebar-menu > li > a > span:not(.pull-right-container):not(.label) {
  flex: 1;
}

/* Logout — subtle danger accent */
.pin-sidebar .pin-sidebar-menu > li.pin-menu-logout > a {
  color: var(--pin-mute) !important;
  margin-top: 8px;
}
.pin-sidebar .pin-sidebar-menu > li.pin-menu-logout > a:hover {
  background: #fde8eb !important;
  color: var(--pin-primary) !important;
}
.pin-sidebar .pin-sidebar-menu > li.pin-menu-logout > a:hover > .fa {
  color: var(--pin-primary) !important;
}

/* Treeview submenu (if used later) */
.pin-sidebar .pin-sidebar-menu .treeview-menu {
  background: var(--pin-surface-soft) !important;
  padding: 4px 0 4px 8px;
  border-radius: var(--pin-r-md);
  margin: 2px 0 4px;
}
.pin-sidebar .pin-sidebar-menu .treeview-menu > li > a {
  font-size: 13px !important;
  padding: 8px 12px 8px 28px !important;
  color: var(--pin-mute) !important;
  border-radius: var(--pin-r-md) !important;
}
.pin-sidebar .pin-sidebar-menu .treeview-menu > li.active > a,
.pin-sidebar .pin-sidebar-menu .treeview-menu > li > a:hover {
  background: var(--pin-surface-card) !important;
  color: var(--pin-ink) !important;
}

/* Sidebar mini mode */
.sidebar-mini.sidebar-collapse .pin-sidebar .pin-user-info { display: none; }
.sidebar-mini.sidebar-collapse .pin-sidebar .pin-user-panel { justify-content: center; padding: 16px 8px; }
</style>

<aside class="main-sidebar pin-sidebar">

    <section class="sidebar">

        <!-- User panel -->
        <div class="pin-user-panel">
            <img src="<?= $directoryAsset ?>/img/avatar04.png" class="pin-user-avatar" alt="User"/>
            <div class="pin-user-info">
                <p class="pin-user-name"><?= Html::encode($username) ?></p>
                <a href="#" class="pin-user-status">
                    <span class="pin-status-dot"></span> Online
                </a>
            </div>
        </div>

        <?= dmstr\widgets\Menu::widget([
            'options' => ['class' => 'sidebar-menu pin-sidebar-menu', 'data-widget' => 'tree'],
            'items' => [
                ['label' => 'Menu', 'options' => ['class' => 'header']],
                ['label' => 'Dashboard',           'icon' => 'bar-chart', 'url' => ['site/dashboard']],
                ['label' => 'Modal Awal',          'icon' => 'server',    'url' => ['transaksi/modal']],
                ['label' => 'Penjualan',           'icon' => 'opencart',  'url' => ['site/index']],
                ['label' => 'Pengeluaran',         'icon' => 'money',     'url' => ['transaksi/pengeluaran']],
                ['label' => 'Kelola Penjualan',    'icon' => 'clone',     'url' => ['transaksi/kelolapenjualan']],
                ['label' => 'Laporan Keuangan', 'icon' => 'archive', 'url' => ['transaksi/laporankeuangan']],
                ['label' => 'Data Barang',         'icon' => 'folder',    'url' => ['filebarang/index']],
                ['label' => 'User',                'icon' => 'user',      'url' => ['user/index']],
                ['label' => 'Setting',             'icon' => 'wrench',    'url' => ['setting/index']],
                [
                    'label'   => 'Logout',
                    'icon'    => 'sign-out',
                    'url'     => ['site/logout'],
                    'visible' => !Yii::$app->user->isGuest,
                    'options' => ['class' => 'pin-menu-logout'],
                ],
            ],
        ]) ?>

    </section>

</aside>
