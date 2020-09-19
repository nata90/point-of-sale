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
                        'actions' => ['logout','index','dashboard','searchgrafik'],
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

        /*$data = FileBarang::find()
        ->select(['nama_barang as value', 'nama_barang as  label','kd_barang as id'])
        ->where(['aktif'=>1])
        ->asArray()
        ->all();*/

        $data = FileStokBarang::find()
        ->select(['file_barang.nama_barang as value', 'IF(file_stok_barang.tgl_ed = "1970-01-01",CONCAT(file_barang.nama_barang, " - ED : #"), CONCAT(file_barang.nama_barang, " - ED : ", DATE_FORMAT(file_stok_barang.tgl_ed, "%d-%m-%Y"))) as label','file_stok_barang.id as id','file_stok_barang.kd_barang as kd_barang'])
        ->join('LEFT JOIN', 'file_barang', 'file_stok_barang.kd_barang = file_barang.kd_barang')
        ->where(['file_barang.aktif'=>1])
        ->asArray()
        ->all();

        $setting = SettingApp::find()->one();

       /*echo '<pre>';
        print_r($data);
        echo '</pre>';
        exit();*/

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
        $kd_barang = $_GET['kodebarang'];
        $qty = $_GET['qty'];
        $id_stok = $_GET['idstok'];

        $subtotal = 0;
        $diskon = 0;
        $total = 0;

        $file_barang = FileBarang::find()->where(['kd_barang'=>$kd_barang])->one();
        $total = $file_barang->harga_jual * $qty;

        $session = new Session;
        $session->open();

        if(!isset($session['datatransaksi'])){
            $array_data = array();
            $array_data[] = array(
                'kodebarang'=>$kd_barang,
                'namabarang'=>$file_barang->nama_barang,
                'qty'=>$qty,
                'harga'=>$file_barang->harga_jual,
                'total'=>$total,
                'idstok'=>$id_stok
            );

           $session['datatransaksi'] = $array_data;
        }else{
            $array_data = $session['datatransaksi'];
            $new_data = array(
                'kodebarang'=>$kd_barang,
                'namabarang'=>$file_barang->nama_barang,
                'qty'=>$qty,
                'harga'=>$file_barang->harga_jual,
                'total'=>$total,
                'idstok'=>$id_stok
            );
            array_push($array_data,$new_data);
            $session['datatransaksi'] = $array_data;
        }


        $table = '<table class="table">
                        <tbody>
                            <tr class="table-title">
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </tbody>';
        if(isset($session['datatransaksi']) && !empty($session['datatransaksi'])){
            $no = 1;
            foreach($session['datatransaksi'] as $key=>$value){
                $subtotal = $subtotal + $value['total'];
                $total = $subtotal - $diskon;
                $table .= '<tr>
                    <td>'.$no.'</td>
                    <td>'.$value['namabarang'].'</td>
                    <td>'.Utility::rupiah($value['harga']).'</td>
                    <td>'.$value['qty'].'</td>
                    <td>'.Utility::rupiah($value['total']).'</td>
                    <td style="text-align:center;"><a rel="'.$key.'" url="'.Url::to(['site/deleteitem']).'" class="delete-item" href="#" title="Delete"><i class="fa fa-trash"></i></a></td>
                </tr>';

                $no++;
            }
        }

        $table .= '</table>';
        
        $arr_return['data'] = $table;
        $arr_return['subtotal'] = '<strong>'.Utility::rupiah($subtotal).'</strong>';
        $arr_return['total'] = '<strong>'.Utility::rupiah($total).'</strong>';
        $arr_return['diskon'] = '<strong>'.Utility::rupiah($diskon).'</strong>';
        $arr_return['hidtotal'] = $total;

        echo Json::encode($arr_return);

    }


    public function actionDeleteitem(){
        $key = $_GET['rel'];

        $subtotal = 0;
        $diskon = 0;
        $total = 0;
        $arr_return = array();

        $session = new Session;
        $session->open();

        $arr_data = $session['datatransaksi'];
        unset($arr_data[$key]);

        $session['datatransaksi'] = $arr_data;

        $table = '<table class="table">
                        <tbody>
                            <tr class="table-title">
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </tbody>';
        if(isset($session['datatransaksi']) && !empty($session['datatransaksi'])){
            $no = 1;
            foreach($session['datatransaksi'] as $key=>$value){
                $subtotal = $subtotal + $value['total'];
                $total = $subtotal - $diskon;
                $table .= '<tr>
                    <td>'.$no.'</td>
                    <td>'.$value['namabarang'].'</td>
                    <td>'.Utility::rupiah($value['harga']).'</td>
                    <td>'.$value['qty'].'</td>
                    <td>'.Utility::rupiah($value['total']).'</td>
                    <td style="text-align:center;"><a rel="'.$key.'" url="'.Url::to(['site/deleteitem']).'" class="delete-item" href="#" title="Delete"><i class="fa fa-trash"></i></a></td>
                </tr>';

                $no++;
            }
        }

        $table .= '</table>';

        $arr_return['data'] = $table;
        $arr_return['subtotal'] = '<strong>'.Utility::rupiah($subtotal).'</strong>';
        $arr_return['total'] = '<strong>'.Utility::rupiah($total).'</strong>';
        $arr_return['hidtotal'] = $total;
        $arr_return['diskon'] = '<strong>'.Utility::rupiah($diskon).'</strong>';

        echo Json::encode($arr_return);
    }


    public function actionSimpantransaksi(){
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
                    $detail->id_stok_barang = $value['idstok'];
                    $detail->save();
                }
            }

            $return['success'] = 1;
            $return['nopenjualan'] = $model->no_transaksi;
            $return['items'] = $arr_item;
            $return['redirect'] = Url::to(['site/resumetransaksi','id'=>$model->id]);
        }

        echo Json::encode($return);
    }

    public function actionResumetransaksi($id){
        $model = HdTransaksi::findOne($id);

        return $this->render('resume',[
            'model'=>$model,
            'id'=>$id
        ]);
    }

    public function actionCanceltransaction($id){
        $model = HdTransaksi::findOne($id);
        $model->status_hapus = 1;
        $model->tgl_hapus = date('Y-m-d H:i:s');

        $return = array();
        if($model->save()){
            
            $return['redirect'] = Url::to(['site/index']);
        }

        echo Json::encode($return);
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
        $model = HdTransaksi::findOne($id);

        $return['data'] = $this->renderPartial('detail_transaksi', [
            'model' => $model,
        ]);
        $return['header'] = 'NO TRANSAKSI : '.$model->no_transaksi;

        echo Json::encode($return);
    }

    public function actionDashboard(){

        $ten_days_ago = mktime(0, 0, 0, date('m'), date('d')-10, date('Y'));
        $days_ago = date('m/d/Y',$ten_days_ago);
        $days_now = date('m/d/Y');

        $convert_days_ago = date('Y-m-d', strtotime($days_ago));
        $convert_days_now = date('Y-m-d', strtotime($days_now));
        
        $popular = HdTransaksi::getProdukTerlaris($convert_days_ago, $convert_days_now);
        $searchModel = new FileBarangSearch();
        $almost_ed = $searchModel->searchBeforeED(Yii::$app->request->queryParams);
        $zero_stok = $searchModel->searchStokKosong(Yii::$app->request->queryParams);

        $model_almost_ed = $almost_ed->getModels();
        $model_zero_stok = $zero_stok->getModels();
        $setting = SettingApp::find()->one();

        return $this->render('dashboard',[
            'days_ago'=>$days_ago,
            'days_now'=>$days_now,
            'popular'=>$popular,
            'model_almost_ed'=>$model_almost_ed,
            'model_zero_stok'=>$model_zero_stok,
            'setting'=>$setting
        ]);
    }

    public function actionGrafikpenjualan(){

        $arr_date = array();
        $arr_data = array();

        for($i=10;$i>=0;$i--){
            $date = mktime(0, 0, 0, date('m'), date('d')-$i, date('Y'));
            $arr_date[] = date('d/m/Y', $date);
            $arr_data[] = HdTransaksi::getTotalTransaksi(date('Y-m-d', $date));
        }
        $return['label'] = $arr_date;
        $return['data'] = $arr_data;

        echo Json::encode($return);
    }

    public function actionSearchgrafik(){
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

        echo Json::encode($return);
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

        echo Json::encode($return);
    }

    public function actionLoaddatabarang(){
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

        echo Json::encode($return);
    }
}
