<?php

namespace app\controllers;

use Yii;
use app\models\DtTransaksi;
use app\models\DtTransaksiSearch;
use app\models\HdTransaksiSearch;
use app\models\HdTransaksi;
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
                'only' => ['index','excelrekap','reportpenjualan','kelolapenjualan'],
                'rules' => [
                    [
                        'actions' => ['index','excelrekap','reportpenjualan','kelolapenjualan'],
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

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
}
