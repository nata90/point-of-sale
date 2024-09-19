<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/avatar04.png" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?php //echo Yii::$app->user->identity->username;?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'MENU', 'options' => ['class' => 'header']],
                    ['label' => 'Dashboard', 'icon' => 'bar-chart', 'url' => ['site/dashboard']],
                    ['label' => 'Penjualan', 'icon' => 'opencart', 'url' => ['site/index']],
                    ['label' => 'Pengeluaran', 'icon' => 'money', 'url' => ['transaksi/pengeluaran']],
                    ['label' => 'Modal', 'icon' => 'server', 'url' => ['transaksi/modal']],
                    ['label' => 'Kelola Penjualan', 'icon' => 'clone', 'url' => ['transaksi/kelolapenjualan']],
                    ['label' => 'Laporan Rekap Penjualan', 'icon' => 'archive', 'url' => ['transaksi/index']],
                    ['label' => 'Master Barang', 'icon' => 'folder', 'url' => ['filebarang/index'],],
                    ['label' => 'User', 'icon' => 'user', 'url' => ['user/index'],],
                    /*['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],*/
                    
                    /* [
                        'label' => 'Master',
                        'icon' => 'book',
                        'url' => '#',
                        'options'=>['class'=>'treeview active'],
                        'items' => [
                            ['label' => 'Barang', 'icon' => 'folder', 'url' => ['filebarang/index'],],
                            ['label' => 'Supplier', 'icon' => 'folder', 'url' => ['supplier/index'],], 
                        ],
                    ], */
                    /* [
                        'label' => 'User',
                        'icon' => 'book',
                        'url' => '#',
                        'options'=>['class'=>'treeview active'],
                        'items' => [
                            ['label' => 'Kelola User', 'icon' => 'folder', 'url' => ['user/index'],],
                            //['label' => 'Kelola User Group', 'icon' => 'folder', 'url' => ['usergroup/index'],],
                        ],
                    ], */
                    ['label' => 'Setting', 'icon' => 'wrench', 'url' => ['setting/index']],
                    ['label' => 'Logout', 'url' => ['site/logout'], 'visible' => !Yii::$app->user->isGuest],
                ],
            ]
        ) ?>

    </section>

</aside>
