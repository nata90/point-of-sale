<?php

namespace app\controllers;

use app\models\Pengeluaran;
use Yii;
use app\models\DtTransaksi;
use app\models\DtTransaksiSearch;
use app\models\HdTransaksiSearch;
use app\models\HeaderPembelianSearch;
use app\models\HdTransaksi;
use app\models\HeaderPembelian;
use app\models\SettingApp;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii2tech\spreadsheet\Spreadsheet;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use app\components\Utility;
use yii\web\Session;
use kartik\mpdf\Pdf;
use yii\helpers\Json;

/**
 * TransaksiController implements the CRUD actions for DtTransaksi model.
 */
class TransaksiController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','excelrekap','reportpenjualan','kelolapenjualan','kelolapembelian','deletepembelian'],
                'rules' => [
                    [
                        'actions' => ['index','excelrekap','reportpenjualan','kelolapenjualan','kelolapembelian','deletepembelian','simpanpengeluaran'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all DtTransaksi models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DtTransaksiSearch();
        $searchModel->start_date = date('Y-m-d');
        $searchModel->end_date = date('Y-m-d');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $setting = SettingApp::find()->one();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'setting'=>$setting
        ]);
    }

    /**
     * Displays a single DtTransaksi model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DtTransaksi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DtTransaksi();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DtTransaksi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DtTransaksi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = HdTransaksi::findOne($id);
        $model->status_hapus = 1;
        $model->tgl_hapus = date('Y-m-d H:i:s');

        if($model->save()){
            return $this->redirect(['kelolapenjualan']);
        }

    }

    public function actionExcelrekap(){
        $session = new Session;
        $session->open();

        $searchModel = new DtTransaksiSearch();
        $searchModel->start_date = $session['start-date'];
        $searchModel->end_date = $session['end-date'];

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $exporter = new Spreadsheet([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'label'=>'No Transaksi',
                    'format'=>'raw',
                    'value'=>function($model){
                        return $model->no_transaksi;
                    },
                ],
                'kd_barang',
                [
                    'label'=>'Tanggal Transaksi',
                    'format'=>'raw',
                    'value'=>function($model){
                        return date('d-m-Y', strtotime($model->header->tgl_bayar));
                    },
                ],
                [
                    'attribute'=>'nama_barang',
                    'format'=>'raw',
                    'value'=>function($model){
                        return $model->barang->nama_barang;
                    },
                ],
                [
                    'attribute'=>'harga_satuan',
                    'format'=>'raw',
                    'value'=>function($model){
                        return $model->harga_satuan;
                    },
                ],
                'qty',
                [
                    'label'=>'Total',
                    'format'=>'raw',
                    'value'=>function($model){
                        return $model->harga_satuan * $model->qty;
                    },
                ],
            ],
        ]);

        $exporter->title = 'Laporan Rekap Penjualan';

        $exporter->headerColumnUnions = 
        [
            [
                'header' => 'LAPORAN PENJUALAN '.date('d/m/Y', strtotime($searchModel->start_date)).' - '.date('d/m/Y', strtotime($searchModel->end_date)),
                'offset' => 0,
                'length' => 6,
            ]
        ];



        return $exporter->send('laporan-penjualan.xls');
    }

    public function actionReportpenjualan(){
        $session = new Session;
        $session->open();

        $searchModel = new DtTransaksiSearch();
        $searchModel->start_date = $session['start-date'];
        $searchModel->end_date = $session['end-date'];

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('report_penjualan_pdf', [
            'model' => $model,
            'searchModel'=>$searchModel
        ]);
        
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}', 
             // set mPDF properties on the fly
            'options' => ['title' => 'LAPORAN REPORT PENJUALAN'],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['LAPORAN REPORT PENJUALAN'], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);
        
        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    public function actionKelolapenjualan(){
        $searchModel = new HdTransaksiSearch();
        $searchModel->tgl_bayar = date('Y-m-d');

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('kelola_penjualan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionKelolapembelian(){
        $searchModel = new HeaderPembelianSearch();
        $searchModel->start_date = date('Y-m-d');
        $searchModel->end_date = date('Y-m-d');

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('kelola_pembelian', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDeletepembelian($id){
        $model = HeaderPembelian::findOne($id);

        $model->status_delete = 1;
        $model->tgl_delete = date('Y-m-d H:i:s');
        $model->save(false);

        return $this->redirect(['kelolapembelian']);
    }

    public function actionSendpenjualan(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $session = new Session;
        $session->open();

        $searchModel = new DtTransaksiSearch();
        $searchModel->start_date = $session['start-date'];
        $searchModel->end_date = $session['end-date'];

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $data = $dataProvider->getModels();

        $total = 0;
        if($data != null){
            foreach($data as $val){
                $total = $total + $val->total_harga;
            }
        }
        
        $format_rp = Utility::rupiah($total);

        if($session['start-date'] == $session['end-date']){
            $date_rate = date('d-m-Y', strtotime($session['start-date']));
        }else{
            $date_rate = date('d-m-Y', strtotime($session['start-date'])).' sampai dengan '.date('d-m-Y', strtotime($session['end-date']));
        }
        


        $exporter = new Spreadsheet([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'label'=>'No Transaksi',
                    'format'=>'raw',
                    'value'=>function($model){
                        return $model->no_transaksi;
                    },
                ],
                'kd_barang',
                [
                    'label'=>'Tanggal Transaksi',
                    'format'=>'raw',
                    'value'=>function($model){
                        return date('d-m-Y', strtotime($model->header->tgl_bayar));
                    },
                ],
                [
                    'attribute'=>'nama_barang',
                    'format'=>'raw',
                    'value'=>function($model){
                        return $model->barang->nama_barang;
                    },
                ],
                [
                    'attribute'=>'harga_satuan',
                    'format'=>'raw',
                    'value'=>function($model){
                        return $model->harga_satuan;
                    },
                ],
                'qty',
                [
                    'label'=>'Total',
                    'format'=>'raw',
                    'value'=>function($model){
                        return $model->harga_satuan * $model->qty;
                    },
                ],
            ],
        ]);

        $exporter->title = 'Laporan Rekap Penjualan';

        $exporter->headerColumnUnions = 
        [
            [
                'header' => 'LAPORAN PENJUALAN '.date('d/m/Y', strtotime($searchModel->start_date)).' - '.date('d/m/Y', strtotime($searchModel->end_date)),
                'offset' => 0,
                'length' => 6,
            ]
        ];

        $name_file = 'penjualan#'.date('d-m-Y', strtotime($session['start-date'])).'#'.date('d-m-Y', strtotime($session['end-date'])).'.xls';
        $exporter->save($name_file);

        //chmod($name_file, 0755);

        $setting = SettingApp::find()->one();
        $rows = array();

        $html = $this->renderPartial('email_template', [
            'setting' => $setting,
            'total'=>$total,
            'format_rp'=>$format_rp,
            'date_rate'=>$date_rate
        ],true,false);

        Yii::$app->mailer->compose()
            ->setFrom('from@domain.com')
            ->setTo($setting->email)
            ->setSubject('Message subject')
            ->setTextBody('Plain text content')
            ->setHtmlBody($html)
            ->attach($_SERVER['DOCUMENT_ROOT'].'/pos/web/'.$name_file)
            ->send();

        unlink($_SERVER['DOCUMENT_ROOT'].'/pos/web/'.$name_file);
        $rows['email'] = $setting->email;

        return $rows;
    }

    /**
     * Finds the DtTransaksi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DtTransaksi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DtTransaksi::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionPengeluaran(){
        $model = new Pengeluaran();

        $pengeluaran_hari_ini = Pengeluaran::find()->where(['BETWEEN', 'tanggal', date('Y-m-d').' 00:00:00', date('Y-m-d').' 23:59:59'])->all();

        return $this->render('pengeluaran', [
            'model' => $model,
            'pengeluaran_hari_ini'=>$pengeluaran_hari_ini
        ]);
    }

    public function actionSimpanpengeluaran(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $deskripsi = Yii::$app->request->post('deskripsi');
        $nilai = Yii::$app->request->post('nilai');

        $return = [];
        $model = new Pengeluaran();
        $model->deskripsi = $deskripsi;
        $model->nilai = $nilai;
        $model->tanggal = date('Y-m-d H:i:s');
        if($model->save()){
            Yii::$app->session->setFlash('success', 'Pengeluaran '.$model->deskripsi.' Berhasil Disimpan');
            $return['success'] = 1;
        }else{
            $return['success'] = 0;
        }

        return $return;
    }
}
