<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Penjualan;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\FileBarang;
use app\models\FileStokBarang;
use app\models\HdTransaksi;
use app\models\DtTransaksi;
use app\models\FileBarangSearch;
use app\models\SettingApp;
use app\components\Utility;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use Exception;

class SiteController extends Controller
{

    public $penjualan;
    public function init()
    {
        parent::init();
        $this->penjualan = new Penjualan();
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout','index','dashboard'],
                'rules' => [
                    [
                        'actions' => ['logout','index','dashboard','searchgrafik','getnamabarang'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            /*'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],*/
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $session = Yii::$app->session;
        unset($session['datatransaksi']);

        $model = new FileBarang();

        $rows = FileBarang::find()
            ->select(['nama_barang', 'harga_jual', 'kd_barang'])
            ->where(['aktif' => 1])
            ->asArray()
            ->all();

        $data = array_map(static function ($row) {
            return [
                'value' => $row['nama_barang'],
                'label' => $row['nama_barang'] . ' | ' . Utility::rupiah($row['harga_jual']),
                'id' => $row['kd_barang'],
            ];
        }, $rows);

        $setting = SettingApp::find()->one();

        return $this->render('index', [
            'model' => $model,
            'data'=>$data,
            'setting'=>$setting
        ]);
        
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {

        if (!Yii::$app->user->isGuest) {
            //return $this->goHome();
            return $this->redirect(['dashboard']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['dashboard']);
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(['login']);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionProsestransaksi(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $kd_barang = Yii::$app->request->get('kodebarang');
        $qty = Yii::$app->request->get('qty');
        $nm_barang = Yii::$app->request->get('namabarang');
        $arr_return = [];
        $arr_return['datafound'] = 1;

        $subtotal = 0;
        $diskon = 0;
        $total = 0;

        $file_barang = FileBarang::find()->where(['kd_barang'=>$kd_barang])->one();
        if($file_barang == null){
            $arr_return['datafound'] = 0;
            $arr_return['msg'] = 'Barang Tidak Ditemukan, Silahkan Cek Barang Anda !';

            return $arr_return;
        }

        if(trim($file_barang->nama_barang) != trim($nm_barang)){
            $arr_return['datafound'] = 0;
            $arr_return['msg'] = 'kode Barang dan Nama Barang Tidak Cocok, Silahkan Cek Barang Anda !';

            return $arr_return;
        }

        $total = $file_barang->harga_jual * $qty;

        $session = Yii::$app->session;

        if(!isset($session['datatransaksi'])){
            $array_data = array();
            $array_data[$kd_barang] = array(
                'kodebarang'=>$kd_barang,
                'namabarang'=>$file_barang->nama_barang,
                'qty'=>$qty,
                'harga'=>$file_barang->harga_jual,
                'total'=>$total,
                //'idstok'=>$id_stok
            );

           $session['datatransaksi'] = $array_data;
        }else{
            $array_data = $session['datatransaksi'];
            if(isset($array_data[$kd_barang])){
                $array_data[$kd_barang]['qty'] = $array_data[$kd_barang]['qty'] + $qty;
                $array_data[$kd_barang]['total'] = $array_data[$kd_barang]['qty']*$file_barang->harga_jual;
            }else{
                $array_data[$kd_barang] = [
                    'kodebarang'=>$kd_barang,
                    'namabarang'=>$file_barang->nama_barang,
                    'qty'=>$qty,
                    'harga'=>$file_barang->harga_jual,
                    'total'=>$total,
                    //'idstok'=>$id_stok
                ];
            }
            
            $session['datatransaksi'] = $array_data;
        }

        $totalTransaksi = Utility::getTotalTransaksiPenjualan($array_data,$diskon);
        
        $arr_return['data'] = $this->renderPartial('data_transaksi',[
            'datatransaksi'=>$session['datatransaksi'],
            'subtotal'=>$subtotal,
            'diskon'=>$diskon,
            'total'=>$total
        ]);
        $arr_return['subtotal'] = '<strong>'.Utility::rupiah($totalTransaksi['subtotal']).'</strong>';
        $arr_return['total'] = '<strong>'.Utility::rupiah($totalTransaksi['total']).'</strong>';
        $arr_return['diskon'] = $diskon;
        $arr_return['hidtotal'] = $totalTransaksi['total'];

        return $arr_return;

    }


    public function actionDeleteitem(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $key = Yii::$app->request->get('rel');;

        $subtotal = 0;
        $diskon = 0;
        $total = 0;
        $arr_return = array();

        $session = Yii::$app->session;

        $arr_data = $session['datatransaksi'];
        unset($arr_data[$key]);

        $session['datatransaksi'] = $arr_data;

        $totalTransaksi = Utility::getTotalTransaksiPenjualan($arr_data,$diskon);

        $arr_return['data'] = $this->renderPartial('data_transaksi',[
            'datatransaksi'=>$arr_data,
            'subtotal'=>$subtotal,
            'diskon'=>$diskon,
            'total'=>$total
        ]);

        $arr_return['subtotal'] = '<strong>'.Utility::rupiah($totalTransaksi['subtotal']).'</strong>';
        $arr_return['total'] = '<strong>'.Utility::rupiah($totalTransaksi['total']).'</strong>';
        $arr_return['hidtotal'] = $totalTransaksi['total'];
        $arr_return['diskon'] = '<strong>'.Utility::rupiah($diskon).'</strong>';

        return $arr_return;
    }


    public function actionSimpantransaksi(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $total_tagihan = Yii::$app->request->post('totaltagihan');
            $total_bayar = Yii::$app->request->post('totalbayar');

            $session = Yii::$app->session;

            if (!isset($session['datatransaksi']) || empty($session['datatransaksi'])) {
                throw new Exception('List Barang Tidak Boleh Kosong !');
            }

            $cartItems = $session['datatransaksi'];

            foreach ($cartItems as $value) {
                $file_barang = FileBarang::find()
                    ->where(['kd_barang' => $value['kodebarang'], 'aktif' => 1])
                    ->one();

                if ($file_barang === null) {
                    throw new Exception('Barang ' . $value['kodebarang'] . ' tidak ditemukan');
                }

                if ((int) $file_barang->stok < (int) $value['qty']) {
                    throw new Exception(
                        'Stok "' . $file_barang->nama_barang . '" tidak mencukupi. Tersedia: ' . (int) $file_barang->stok
                    );
                }
            }

            $model = new HdTransaksi();
            $model->no_transaksi = Utility::getNoTransaksi(1);
            $model->tgl_bayar = date('Y-m-d H:i:s');
            $model->status_bayar = 1;
            $model->total = $total_tagihan;
            $model->jumlah_bayar = $total_bayar;

            if (!$model->save()) {
                throw new Exception($this->formatErrors($model->getErrors()));
            }

            $arr_item = [];

            foreach ($cartItems as $value) {
                $file_barang = FileBarang::find()
                    ->where(['kd_barang' => $value['kodebarang']])
                    ->one();

                $arr_item[] = $file_barang->nama_barang . ' : ' . $value['qty'] . ' item';

                $detail = new DtTransaksi();
                $detail->no_transaksi = $model->no_transaksi;
                $detail->kd_barang = $value['kodebarang'];
                $detail->harga_satuan = $value['harga'];
                $detail->qty = $value['qty'];
                $detail->total_harga = $value['harga'] * $value['qty'];
                $detail->id_stok_barang = 0;

                if (!$detail->save()) {
                    throw new Exception($this->formatErrors($detail->getErrors()));
                }

                // Kurangi stok utama barang
                $file_barang->stok = (int) $file_barang->stok - (int) $value['qty'];
                if (!$file_barang->save(false)) {
                    throw new Exception($this->formatErrors($file_barang->getErrors()));
                }

                // Kurangi stok batch (file_stok_barang) dengan metode FEFO (ed terlama dijual lebih dulu)
                $this->kurangiStokBatch($value['kodebarang'], (int) $value['qty']);
            }

            HdTransaksi::cetakNota($model->no_transaksi);

            $transaction->commit();

            unset($session['datatransaksi']);

            return [
                'success' => 1,
                'nopenjualan' => $model->no_transaksi,
                'items' => $arr_item,
                'redirect' => Url::to(['site/resumetransaksi', 'id' => $model->id]),
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'success' => 0,
                'msg' => $e->getMessage(),
            ];
        }
    }

    public function actionResumetransaksi($id){
        $model = HdTransaksi::findOne($id);

        return $this->render('resume',[
            'model'=>$model,
            'id'=>$id
        ]);
    }

    public function actionCanceltransaction($id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model = HdTransaksi::findOne($id);
            $model->status_hapus = 1;
            $model->tgl_hapus = date('Y-m-d H:i:s');

            $return = array();
            if($model->save(false)){
                // Kembalikan stok barang yang sudah terjual
                $this->kembalikanStokPenjualan($model->no_transaksi);
                $transaction->commit();
                $return['redirect'] = Url::to(['site/index']);
            }else{
                throw new Exception($this->formatErrors($model->getErrors()));
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            $return['msg'] = $e->getMessage();
        }

        return $return;
    }

    /**
     * Mengembalikan stok (utama + batch) untuk transaksi penjualan yang dibatalkan.
     */
    private function kembalikanStokPenjualan($no_transaksi)
    {
        $details = DtTransaksi::find()->where(['no_transaksi' => $no_transaksi])->all();

        foreach ($details as $detail) {
            $file_barang = FileBarang::find()->where(['kd_barang' => $detail->kd_barang])->one();
            if ($file_barang !== null) {
                $file_barang->stok = (int) $file_barang->stok + (int) $detail->qty;
                $file_barang->save(false);
            }

            $this->tambahStokBatch($detail->kd_barang, $detail->qty, $detail->id_stok_barang);
        }
    }

    public function actionRekaptransaksi(){
        $dataProvider = new ActiveDataProvider([
            'query' => HdTransaksi::find(),
        ]);

        return $this->render('rekap_transaksi', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDetailtransaksi($id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = HdTransaksi::findOne($id);

        $return['data'] = $this->renderPartial('detail_transaksi', [
            'model' => $model,
        ]);
        $return['header'] = 'NO TRANSAKSI : '.$model->no_transaksi;

        return $return;
    }

    public function actionDashboard(){

        $ten_days_ago = mktime(0, 0, 0, date('m'), date('d')-10, date('Y'));
        $days_ago = date('m/d/Y',$ten_days_ago);
        $days_now = date('m/d/Y');

        $convert_days_ago = date('Y-m-d', strtotime($days_ago));
        $convert_days_now = date('Y-m-d', strtotime($days_now));

        $total_penjualan = $this->penjualan->getTotalPenjualan(date('Y-m-d'), date('Y-m-d'));
        $total_penjualan_kemarin = $this->penjualan->getTotalPenjualan(date('Y-m-d', strtotime('-1 day')), date('Y-m-d', strtotime('-1 day')));

        $total_transaksi = $this->penjualan->getTotalTransaksi(date('Y-m-d'), date('Y-m-d'));
        $total_transaksi_kemarin = $this->penjualan->getTotalTransaksi(date('Y-m-d', strtotime('-1 day')), date('Y-m-d', strtotime('-1 day')));

        $total_item_terjual = $this->penjualan->getTotalItemTerjual(date('Y-m-d'), date('Y-m-d'));

        $transaksi_terbaru = $this->penjualan->getTransaksiTerbaru(7);
        
        $popular = HdTransaksi::getProdukTerlaris($convert_days_ago, $convert_days_now);

        // Stok menipis: barang aktif dengan stok <= min_stok (ambang minimal)
        $stok_menipis = FileBarang::find()
            ->where(['aktif' => 1])
            ->andWhere(['>', 'stok', 0])
            ->andWhere('stok <= IFNULL(min_stok, 5)')
            ->orderBy(['stok' => SORT_ASC])
            ->limit(5)
            ->all();

        $setting = SettingApp::find()->one();

        return $this->render('dashboard',[
            'days_ago'=>$days_ago,
            'days_now'=>$days_now,
            'popular'=>$popular,
            'setting'=>$setting,
            'total_penjualan'=>$total_penjualan,
            'total_transaksi'=>$total_transaksi,
            'total_item_terjual'=>$total_item_terjual,
            'total_penjualan_kemarin'=>$total_penjualan_kemarin,
            'total_transaksi_kemarin'=>$total_transaksi_kemarin,
            'transaksi_terbaru'=>$transaksi_terbaru,
            'stok_menipis'=>$stok_menipis
        ]);
    }

    public function actionGrafikpenjualan(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $arr_date = array();
        $arr_data = array();

        for($i=10;$i>=0;$i--){
            $date = mktime(0, 0, 0, date('m'), date('d')-$i, date('Y'));
            $arr_date[] = date('d/m/Y', $date);
            $arr_data[] = HdTransaksi::getTotalTransaksi(date('Y-m-d', $date));
        }
        $return['label'] = $arr_date;
        $return['data'] = $arr_data;

        return $return;
    }

    public function actionSearchgrafik(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $date_range = $_GET['daterange'];
        $explode = explode('-', $date_range);
        $date_start = date('Y-m-d', strtotime(trim($explode[0])));
        $date_end = date('Y-m-d', strtotime(trim($explode[1])));

        $new_date_1 = new \DateTime($date_start);
        $new_date_2 = new \DateTime($date_end);
        $difference = $new_date_1->diff($new_date_2);

        for($i=0;$i<=$difference->days;$i++){
            $date = mktime(0, 0, 0, date('m', strtotime($date_start)), date('d', strtotime($date_start))+$i, date('Y', strtotime($date_start)));
            $arr_date[] = date('d/m/Y', $date);
            $arr_data[] = HdTransaksi::getTotalTransaksi(date('Y-m-d', $date));
            $arr_rgba[] = 'rgba('.rand(0, 255).', '.rand(0, 255).', '.rand(0, 255).', 0.5)';
        }

        $popular = HdTransaksi::getProdukTerlaris($date_start, $date_end);
        $return['html'] = $this->renderPartial('produk_terlaris', [
            'popular' => $popular,
        ]);

        $return['label'] = $arr_date;
        $return['data'] = $arr_data;
        $return['rgba'] = $arr_rgba;

        return $return;
    }

    public function actionNotifikasi()
    {
       $temp = \app\models\History::find()
                   ->orderBy(['id' => SORT_DESC])
                   ->all();
       foreach ($temp as $value) {
          echo "<p>New Notifikasi</p>";
       }
    }

    public function actionPenjualan(){
        return $this->render('penjualan');
    }

    public function actionAutocompletebarang(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $query = $_GET['query'];

        $model = FileStokBarang::find()
        ->select(['file_barang.nama_barang as nama_barang','file_stok_barang.kd_barang as kd_barang'])
        ->join('LEFT JOIN', 'file_barang', 'file_stok_barang.kd_barang = file_barang.kd_barang')
        ->where(['file_barang.aktif'=>1])
        ->andFilterWhere(['like', 'file_barang.nama_barang', $query])
        ->asArray()
        ->all();

        $arr_data = array();

        if($model != null){
            foreach($model as $val){
                $arr_data[] = $val['nama_barang'];
            }
        }

        $return['data'] = $arr_data;

        return $return;
    }

    public function actionLoaddatabarang(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $value = $_GET['value'];

        $model = FileBarang::find()->where(['nama_barang'=>$value, 'aktif'=>1])->one();

        $arr_ed = array();
        $arr_stok = array();
        if($model != null){
            foreach($model->detailStok as $val){
                $arr_ed[] = date('d-m-Y', strtotime($val->tgl_ed));
                $arr_stok[] = $val->stok_akhir;
            }
        }

        $return['kode'] = $model->kd_barang;
        $return['harga'] = $model->harga_jual;
        $return['ed'] = $arr_ed;
        $return['defed'] = $arr_ed[0];
        $return['jumlah'] = 1;
        $return['stok'] = $arr_stok[0];
        $return['diskon'] = '0';
        $return['total'] = $model->harga_jual;

        return $return;
    }

    public function actionGetnamabarang(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $kode_barang = Yii::$app->request->get('kode_barang');

        $barang = FileBarang::find()->where(['kd_barang'=>$kode_barang])->one();

        $return = [];
        $return['itemfound'] = 0;
        if($barang){
            $return['itemfound'] = 1;
            $return['kd_barang'] = $barang->kd_barang;
            $return['nama_barang'] = $barang->nama_barang;
        }

        return $return;

    }

    private function formatErrors($errors) {
        $errorMessages = '<ul style="text-align: left;">';
        foreach ($errors as $fieldName => $fieldErrors) {
            foreach ($fieldErrors as $error) {
                $errorMessages .= '<li>' . strtoupper($error) . '</li>';
            }
        }
        $errorMessages .= '</ul>';
        return $errorMessages;
    }

    /**
     * Mengurangi stok batch (file_stok_barang) memakai metode FEFO:
     * batch dengan tgl_ed terlama (belum lewat) diambil lebih dulu.
     * Batch placeholder tanpa ED (1970-01-01) menjadi cadangan terakhir.
     */
    private function kurangiStokBatch($kd_barang, $qty)
    {
        $sisa = (float) $qty;
        if ($sisa <= 0) {
            return;
        }

        // Batch ber-ED (belum lewat), urut dari ED terlama (FEFO)
        $batches = FileStokBarang::find()
            ->where(['kd_barang' => $kd_barang])
            ->andWhere(['>', 'tgl_ed', date('Y-m-d')])
            ->andWhere(['>', 'stok_akhir', 0])
            ->orderBy(['tgl_ed' => SORT_ASC])
            ->all();

        // Batch placeholder (tanpa ED / 1970-01-01) dan batch yang sudah lewat ED
        $sisa_batches = FileStokBarang::find()
            ->where(['kd_barang' => $kd_barang])
            ->andWhere(['<=', 'tgl_ed', date('Y-m-d')])
            ->andWhere(['>', 'stok_akhir', 0])
            ->orderBy(['tgl_ed' => SORT_ASC])
            ->all();

        foreach (array_merge($batches, $sisa_batches) as $batch) {
            if ($sisa <= 0) {
                break;
            }
            $tersedia = (float) $batch->stok_akhir;
            if ($tersedia <= 0) {
                continue;
            }
            if ($tersedia >= $sisa) {
                $batch->stok_akhir = $tersedia - $sisa;
                $sisa = 0;
            } else {
                $batch->stok_akhir = 0;
                $sisa = $sisa - $tersedia;
            }
            if (!$batch->save()) {
                throw new Exception('Gagal mengupdate stok batch ' . $batch->kd_barang);
            }
        }
    }

    /**
     * Mengembalikan (menambah) stok batch sesuai detail transaksi yang dibatalkan.
     * Pencocokan batch memakai id_stok_barang jika tersedia, bila tidak maka
     * dimasukkan ke batch dengan tgl_ed yang paling dekat (terlama) atau batch placeholder.
     */
    private function tambahStokBatch($kd_barang, $qty, $id_stok_barang = null)
    {
        // Jika menyimpan referensi batch asal saat penjualan, kembalikan ke batch tsb.
        if ($id_stok_barang) {
            $batch = FileStokBarang::findOne($id_stok_barang);
            if ($batch !== null && $batch->kd_barang == $kd_barang) {
                $batch->stok_akhir = (float) $batch->stok_akhir + (float) $qty;
                $batch->save();
                return;
            }
        }

        // Fallback: tambahkan ke batch dengan ED terdekat yang belum lewat, atau batch placeholder.
        $batch = FileStokBarang::find()
            ->where(['kd_barang' => $kd_barang])
            ->orderBy(['tgl_ed' => SORT_ASC])
            ->one();

        if ($batch !== null) {
            $batch->stok_akhir = (float) $batch->stok_akhir + (float) $qty;
            $batch->save();
        }
    }
}
