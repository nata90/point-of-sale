<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/ionicons.min.css',
        'css/googlefont.css',
        'css/daterangepicker.css',
        'css/jquery.toast.css',
        'css/handsontable.min.css',
    ];
    public $js = [
        'js/Chart.js',
        'js/custom.js',
        'js/moment.min.js',
        'js/daterangepicker.js',
        'js/jquery.toast.js',
        //'js/socket.io.js',
        //'js/notif.js',
        'js/handsontable.full.min.js',
        'js/sweetalert.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
