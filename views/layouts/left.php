<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/avatar04.png" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?php echo Yii::$app->user->identity->username;?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'MENU', 'options' => ['class' => 'header']],
                    ['label' => 'Penjualan', 'icon' => 'opencart', 'url' => ['site/index']],
                    ['label' => 'Laporan Rekap Penjualan', 'icon' => 'archive', 'url' => ['transaksi/index']],
                    /*['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],*/
                    
                    [
                        'label' => 'Master',
                        'icon' => 'book',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Barang', 'icon' => 'folder', 'url' => ['filebarang/index'],],
                        ],
                    ],
                    [
                        'label' => 'User',
                        'icon' => 'book',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Kelola User', 'icon' => 'folder', 'url' => ['user/index'],],
                            //['label' => 'Kelola User Group', 'icon' => 'folder', 'url' => ['usergroup/index'],],
                        ],
                    ],
                    ['label' => 'Logout', 'url' => ['site/logout'], 'visible' => !Yii::$app->user->isGuest],
                ],
            ]
        ) ?>

    </section>

</aside>
