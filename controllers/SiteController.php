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
use app\models\HdTransaksi;
use app\models\DtTransaksi;
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
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
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
        ->select(['nama_barang as value', 'nama_barang as  label','kd_barang as id'])
        ->where(['aktif'=>1])
        ->asArray()
        ->all();

        return $this->render('index', [
            'model' => $model,
            'data'=>$data
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
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['index']);
        }

        $model->password = '';
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

        return $this->goHome();
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
                'total'=>$total
            );

           $session['datatransaksi'] = $array_data;
        }else{
            $array_data = $session['datatransaksi'];
            $new_data = array(
                'kodebarang'=>$kd_barang,
                'namabarang'=>$file_barang->nama_barang,
                'qty'=>$qty,
                'harga'=>$file_barang->harga_jual,
                'total'=>$total
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

        $model = new HdTransaksi;
        $model->no_transaksi = Utility::getNoTransaksi(1);
        $model->tgl_bayar = date('Y-m-d H:i:s');
        $model->status_bayar = 1;
        $model->total = $total_tagihan;
        $model->jumlah_bayar = $total_bayar;
        if($model->save()){
            if(isset($session['datatransaksi']) && !empty($session['datatransaksi'])){
                foreach($session['datatransaksi'] as $key=>$value){
                    $detail = new DtTransaksi;
                    $detail->no_transaksi = $model->no_transaksi;
                    $detail->kd_barang = $value['kodebarang'];
                    $detail->harga_satuan = $value['harga'];
                    $detail->qty = $value['qty'];
                    $detail->total_harga = $value['harga'] * $value['qty'];
                    $detail->save();
                }
            }

            $return['success'] = 1;
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
        $model->status_hapus = 0;
        $model->tgl_hapus = date('Y-m-d H:i:s');

        $return = array();
        if($model->save()){
            $criteria = new CDbCriteria;
            $criteria->condition = 'no_transaksi = :not';
            $criteria->params = array(':not'=>$model->no_transaksi);

            DtTransaksi::model()->updateAll(array('status_hapus'=>1, 'tgl_hapus'=>date('Y-m-d H:i:s')),$criteria);
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

    
}
