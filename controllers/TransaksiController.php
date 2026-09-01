<?php

namespace app\controllers;

use app\models\Modal;
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
                'only' => ['index','excelrekap','reportpenjualan','kelolapenjualan','kelolapembelian','deletepembelian','hitungtotalpenjualan'],
                'rules' => [
                    [
                        'actions' => ['index','excelrekap','reportpenjualan','kelolapenjualan','kelolapembelian','deletepembelian','simpanpengeluaran','cetaknota','hitungtotalpenjualan','hitungpembayaran','laporankeuangan'],
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

        $rupiah_total = Utility::rupiah(HdTransaksi::getTotalRupiahJual(date('Y-m-d'),date('Y-m-d')));
        $qty_total = HdTransaksi::getTotalItemJual(date('Y-m-d'),date('Y-m-d'));

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'setting'=>$setting,
            'rupiah_total'=>$rupiah_total,
            'qty_total'=>$qty_total
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
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model = HdTransaksi::findOne($id);
            $model->status_hapus = 1;
            $model->tgl_hapus = date('Y-m-d H:i:s');

            if($model->save(false)){
                // Kembalikan stok barang yang sudah terjual
                $this->kembalikanStokPenjualan($model->no_transaksi);
                $transaction->commit();
                return $this->redirect(['kelolapenjualan']);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['kelolapenjualan']);
    }

    /**
     * Mengembalikan stok (utama + batch) untuk transaksi penjualan yang dibatalkan.
     */
    private function kembalikanStokPenjualan($no_transaksi)
    {
        $details = DtTransaksi::find()->where(['no_transaksi' => $no_transaksi])->all();

        foreach ($details as $detail) {
            $file_barang = \app\models\FileBarang::find()->where(['kd_barang' => $detail->kd_barang])->one();
            if ($file_barang !== null) {
                $file_barang->stok = (int) $file_barang->stok + (int) $detail->qty;
                $file_barang->save(false);
            }

            $this->tambahStokBatch($detail->kd_barang, $detail->qty, $detail->id_stok_barang);
        }
    }

    private function tambahStokBatch($kd_barang, $qty, $id_stok_barang = null)
    {
        if ($id_stok_barang) {
            $batch = \app\models\FileStokBarang::findOne($id_stok_barang);
            if ($batch !== null && $batch->kd_barang == $kd_barang) {
                $batch->stok_akhir = (float) $batch->stok_akhir + (float) $qty;
                $batch->save();
                return;
            }
        }

        $batch = \app\models\FileStokBarang::find()
            ->where(['kd_barang' => $kd_barang])
            ->orderBy(['tgl_ed' => SORT_ASC])
            ->one();

        if ($batch !== null) {
            $batch->stok_akhir = (float) $batch->stok_akhir + (float) $qty;
            $batch->save();
        }
    }

    public function actionExcelrekap(){
        $session = Yii::$app->session;

        $searchModel = new DtTransaksiSearch();
        $searchModel->start_date = $session['start-date'];
        $searchModel->end_date = $session['end-date'];

        $dataProvider = $searchModel->searchReport(Yii::$app->request->queryParams);

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
                [
                    'label'=>'Kode Barang',
                    'format'=>'raw',
                    'value'=>function($model){
                        return "`".$model->kd_barang;
                    },
                ],
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
        $session = Yii::$app->session;

        $searchModel = new DtTransaksiSearch();
        $searchModel->start_date = $session['start-date'];
        $searchModel->end_date = $session['end-date'];

        $setting = SettingApp::findOne(1);

        $dataProvider = $searchModel->searchReport(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('report_penjualan_pdf', [
            'model' => $model,
            'searchModel'=>$searchModel,
            'setting'=>$setting
        ]);
        
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_LANDSCAPE, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}
            body {
                font-family: Arial, sans-serif;
            }
        
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
        
            th, td {
                border: none;
                padding: 8px;
                text-align: left;
            }
        
            th {
                background-color: #f2f2f2;
                font-weight: bold;
            }
        
            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
        
            tr:hover {
                background-color: #e2e2e2;
            }
        
            caption {
                caption-side: top;
                font-size: 18px;
                font-weight: bold;
                margin: 10px 0;
            }', 
             // set mPDF properties on the fly
            'options' => ['title' => 'LAPORAN PENJUALAN '.$setting->app_name],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['LAPORAN PENJUALAN '.strtoupper($setting->app_name)], 
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
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model = HeaderPembelian::findOne($id);

            $model->status_delete = 1;
            $model->tgl_delete = date('Y-m-d H:i:s');
            $model->save(false);

            // Kurangi stok yang masuk dari pembelian yang dihapus
            $this->kurangiStokPembelian($model->id_pembelian);

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['kelolapembelian']);
    }

    /**
     * Mengurangi stok (utama + batch) akibat pembelian yang dihapus.
     */
    private function kurangiStokPembelian($id_pembelian)
    {
        $details = \app\models\DetailPembelian::find()->where(['id_pembelian' => $id_pembelian])->all();

        foreach ($details as $detail) {
            $file_barang = \app\models\FileBarang::find()->where(['kd_barang' => $detail->kd_barang])->one();
            if ($file_barang !== null) {
                $file_barang->stok = max(0, (int) $file_barang->stok - (int) $detail->jumlah);
                $file_barang->save(false);
            }

            $this->kurangiStokBatch($detail->kd_barang, (int) $detail->jumlah);
        }
    }

    private function kurangiStokBatch($kd_barang, $qty)
    {
        $sisa = (float) $qty;
        if ($sisa <= 0) {
            return;
        }

        $batches = \app\models\FileStokBarang::find()
            ->where(['kd_barang' => $kd_barang])
            ->andWhere(['>', 'tgl_ed', date('Y-m-d')])
            ->andWhere(['>', 'stok_akhir', 0])
            ->orderBy(['tgl_ed' => SORT_ASC])
            ->all();

        $sisa_batches = \app\models\FileStokBarang::find()
            ->where(['kd_barang' => $kd_barang])
            ->andWhere(['<=', 'tgl_ed', date('Y-m-d')])
            ->andWhere(['>', 'stok_akhir', 0])
            ->orderBy(['tgl_ed' => SORT_ASC])
            ->all();

        foreach (array_merge($batches, $sisa_batches) as $batch) {
            if ($sisa <= 0) {
                break;
            }
            if ((float) $batch->stok_akhir <= 0) {
                continue;
            }
            if ((float) $batch->stok_akhir >= $sisa) {
                $batch->stok_akhir = (float) $batch->stok_akhir - $sisa;
                $sisa = 0;
            } else {
                $sisa = $sisa - (float) $batch->stok_akhir;
                $batch->stok_akhir = 0;
            }
            $batch->save();
        }
    }

    public function actionSendpenjualan(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $session = Yii::$app->session;

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

    public function actionCetaknota(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = Yii::$app->request->get('id');

        HdTransaksi::cetakNota($id);

        return ['success'=>1];
    }

    public function actionHitungtotalpenjualan(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $session = Yii::$app->session;

        $start_date = date('Y-m-d', strtotime($session['start-date']));
        $end_date = date('Y-m-d', strtotime($session['end-date']));

        $return = [];

        $return['rupiahtotal'] = Utility::rupiah(HdTransaksi::getTotalRupiahJual($start_date,$end_date));
        $return['qtytotal'] = HdTransaksi::getTotalItemJual($start_date,$end_date);

        return $return;
    }

    public function actionHitungpembayaran(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $jumlah_bayar = Yii::$app->request->get('jumlah_bayar');
        $total_bayar = Yii::$app->request->get('total_bayar');

        if($jumlah_bayar == 'pas'){
            $jumlah_bayar = $total_bayar;
        }

        $kembali = (int)$jumlah_bayar - (int)$total_bayar;

        $return = [];

        $return['jumlahbayar'] = $jumlah_bayar;
        $return['kembali'] = $kembali;

        return $return;

    }

    public function actionModal(){
        $model = new Modal();

        $modal_hari_ini = Modal::find()->where(['tanggal'=>date('Y-m-d')])->one();

        return $this->render('modal', [
            'model' => $model,
            'modal_hari_ini'=>$modal_hari_ini
        ]);
    }

    public function actionSimpanmodal(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {

            $keterangan = Yii::$app->request->post('keterangan');
            $modal = Yii::$app->request->post('modal');

            $return = [];
            $model = Modal::find()->where(['tanggal'=>date('Y-m-d')])->one();
            if($model == null){
                $model = new Modal();
            }
            $model->keterangan = $keterangan;
            $model->modal_awal = $modal;
            $model->tanggal = date('Y-m-d');
            if($model->save()){
                Yii::$app->session->setFlash('success', 'Modal Awal Berhasil Disimpan');
                $return['success'] = 1;
            }else{
                $return['success'] = 0;
            }
        } catch (\Exception $e) {
            $return['success'] = 0;
            $return['msg'] = $e->getMessage();
        }

        return $return;
    }

    public function actionDeletepengeluaran(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            $id = Yii::$app->request->get('id');

            $return = [];
            $model = Pengeluaran::findOne($id);
            if($model->delete()){
                Yii::$app->session->setFlash('success', 'Pengeluaran '.$model->deskripsi.' Berhasil Dihapus');
                $return['success'] = 1;
            }else{
                $return['success'] = 0;
            }
        } catch (\Exception $e) {
            $return['success'] = 0;
            $return['msg'] = $e->getMessage();
        }

        return $return;
    }

    public function actionLaporankeuangan(){
        $searchModel = new DtTransaksiSearch();
        $searchModel->start_date = date('Y-m-d');
        $searchModel->end_date = date('Y-m-d');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $setting = SettingApp::find()->one();

        $modal_awal = Modal::getModalAwal(date('Y-m-d'));
        $total_penjualan = HdTransaksi::getTotalRupiahJual(date('Y-m-d'),date('Y-m-d'));
        $pengeluaran = Pengeluaran::getTotalPengeluaran(date('Y-m-d'),date('Y-m-d'));
        $keuntungan = $modal_awal + $total_penjualan - $pengeluaran;

        

        return $this->render('laporan_keuangan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'setting'=>$setting,
            'modal_awal'=>$modal_awal,
            'total_penjualan'=>$total_penjualan,
            'pengeluaran'=>$pengeluaran,
            'keuntungan'=>$keuntungan
        ]);
    }
}
