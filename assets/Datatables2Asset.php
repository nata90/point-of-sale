<?php

namespace app\assets;

use yii\web\AssetBundle;


class Datatables2Asset extends AssetBundle
{
  public $basePath = '@webroot';
  public $baseUrl = '@web';

  public $css = [
    // 'css/jquery.dataTables.min.css',
    // 'css/bootstrap.min.css',
    'dist/dataTable/css/dataTables-2.1.4.css',
    'dist/dataTable/css/fixedColumns.dataTables.css',
    'dist/dataTable/css/buttons.dataTables.css',
    'dist/dataTable/css/dataTableResponsive.css',
  ];

  public $js = [
    'dist/dataTable/js/dataTables-2.1.4.js',
    'dist/dataTable/js/dataTables.fixedHeader.min.js',
    'dist/dataTable/js/fixedColumns.dataTables.js',
    'dist/dataTable/js/dataTables.fixedColumns.js',
    'dist/dataTable/js/dataTables.buttons.js',
    'dist/dataTable/js/buttons.dataTables.js',
    'dist/dataTable/js/buttons.html5.min.js',
    'dist/dataTable/js/jszip.min.js',
    'dist/dataTable/js/dataTableResponsive.js',
  ];

  public $publishOptions = [
    'forceCopy' => true,
  ];
  public $depends = [
    'yii\web\YiiAsset',
  ];
}
