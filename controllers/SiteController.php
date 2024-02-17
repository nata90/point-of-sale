<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
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
use yii\web\Session;
use yii\data\ActiveDataProvider;

class SiteController extends Controller
{
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
        $session = new Session;
        $session->open();
        unset($session['datatransaksi']);

        $model = new FileBarang();

        $data = FileBarang::find()
        ->select(['nama_barang as value', 'CONCAT(nama_barang, " | ", CONCAT("Rp ", FORMAT(harga_jual, 0))) as  label','kd_barang as id'])
        ->where(['aktif'=>1])
        ->asArray()
        ->all();

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
            return $this->redirect(['index']);
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

        $session = new Session;
        $session->open();

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

        $session = new Session;
        $session->open();

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

        $total_tagihan = $_POST['totaltagihan'];
        $total_bayar = $_POST['totalbayar'];
        $cashback = $_POST['cashback'];

        $session = new Session;
        $session->open();

        $return = array();
        $arr_item = array();

        $model = new HdTransaksi;
        $model->no_transaksi = Utility::getNoTransaksi(1);
        $model->tgl_bayar = date('Y-m-d H:i:s');
        $model->status_bayar = 1;
        $model->total = $total_tagihan;
        $model->jumlah_bayar = $total_bayar;
        if($model->save()){
            if(isset($session['datatransaksi']) && !empty($session['datatransaksi'])){
                foreach($session['datatransaksi'] as $key=>$value){
                    $nama_barang = FileBarang::find()->where(['kd_barang'=>$value['kodebarang']])->one();
                    $arr_item[] = $nama_barang->nama_barang.' : '.$value['qty'].' item';
                    $detail = new DtTransaksi;
                    $detail->no_transaksi = $model->no_transaksi;
                    $detail->kd_barang = $value['kodebarang'];
                    $detail->harga_satuan = $value['harga'];
                    $detail->qty = $value['qty'];
                    $detail->total_harga = $value['harga'] * $value['qty'];
                    $detail->id_stok_barang = 0;
                    $detail->save();
                }
            }

            HdTransaksi::cetakNota($model->no_transaksi);

            $return['success'] = 1;
            $return['nopenjualan'] = $model->no_transaksi;
            $return['items'] = $arr_item;
            $return['redirect'] = Url::to(['site/resumetransaksi','id'=>$model->id]);
        }

        return $return;
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

        $model = HdTransaksi::findOne($id);
        $model->status_hapus = 1;
        $model->tgl_hapus = date('Y-m-d H:i:s');

        $return = array();
        if($model->save()){
            
            $return['redirect'] = Url::to(['site/index']);
        }

        return $return;
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
        
        $popular = HdTransaksi::getProdukTerlaris($convert_days_ago, $convert_days_now);

        $setting = SettingApp::find()->one();

        return $this->render('dashboard',[
            'days_ago'=>$days_ago,
            'days_now'=>$days_now,
            'popular'=>$popular,
            'setting'=>$setting
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
}
