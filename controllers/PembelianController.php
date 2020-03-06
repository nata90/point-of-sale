<?php

namespace app\controllers;

use Yii;
use app\models\HeaderPembelian;
use app\models\DetailPembelian;
use app\models\HeaderPembelianSearch;
use app\models\FileBarang;
use app\models\Supplier;
use app\models\KodeGenerate;
use app\components\Utility;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Session;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * PembelianController implements the CRUD actions for HeaderPembelian model.
 */
class PembelianController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','create','listpembelian','deleteitem','loadformbarang', 'simpanpembelian','autocompletebarang'],
                'rules' => [
                    [
                        'actions' => ['index','create','listpembelian','deleteitem','loadformbarang','simpanpembelian','autocompletebarang','autocompletesupplier'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all HeaderPembelian models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HeaderPembelianSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HeaderPembelian model.
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
     * Creates a new HeaderPembelian model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $session = new Session;
        $session->open();

        unset($session['datapembelian']);

        $model = new HeaderPembelian();
        $model->tgl_pembelian = date('Y-m-d');

        $data = FileBarang::find()
        ->select(['nama_barang as value', 'nama_barang as  label','kd_barang as id'])
        ->where(['aktif'=>1])
        ->asArray()
        ->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_pembelian]);
        }

        return $this->render('create', [
            'model' => $model,
            'data'=>$data
        ]);
    }

    /**
     * Updates an existing HeaderPembelian model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_pembelian]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing HeaderPembelian model.
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

    public function actionListpembelian(){
        $tgl = $_POST['tgl'];
        $kd_bar = $_POST['kd_bar'];
        $nm_barang = $_POST['nm_barang'];
        $jumlah = $_POST['jumlah'];
        $harga_beli = (int)$_POST['harga_beli'];
        $harga_jual = (int)$_POST['harga_jual'];

        $session = new Session;
        $session->open();

        if(!isset($session['datapembelian'])){
            $array_data = array();
            $array_data[] = array(
                'tgl'=>$tgl,
                'kodebarang'=>$kd_bar,
                'namabarang'=>$nm_barang,
                'jumlah'=>$jumlah,
                'hargabeli'=>$harga_beli,
                'hargajual'=>$harga_jual
            );

           $session['datapembelian'] = $array_data;
        }else{
            $array_data = $session['datapembelian'];
            $new_data = array(
                'tgl'=>$tgl,
                'kodebarang'=>$kd_bar,
                'namabarang'=>$nm_barang,
                'jumlah'=>$jumlah,
                'hargabeli'=>$harga_beli,
                'hargajual'=>$harga_jual
            );
            array_push($array_data,$new_data);
            $session['datapembelian'] = $array_data;
        }

        $data['table'] = $this->renderPartial('list_pembelian', [
            'arr_data' => $session['datapembelian'],
        ],true,false);

        echo Json::encode($data);
    }

     public function actionDeleteitem(){
        $key = $_GET['key'];

        $session = new Session;
        $session->open();

        $arr_data = $session['datapembelian'];
        unset($arr_data[$key]);

        $session['datapembelian'] = $arr_data;

        $data['table'] = $this->renderPartial('list_pembelian', [
            'arr_data' => $session['datapembelian'],
        ],true,false);

        echo Json::encode($data);
     }

    public function actionLoadformbarang(){
        $model = new FileBarang();
        $kode_barang = Utility::generateKodeBarang();
        $model->kd_barang = $kode_barang;

        $data['title'] = '<strong>TAMBAH BARANG</strong>';
        $data['msg'] = $this->renderPartial('ajax_form_barang', [
            'model' => $model,
        ],true,false);

        echo Json::encode($data);
    }

    public function actionSimpanbarang(){
        $kd_barang = $_POST['kd_barang'];
        $nama_barang = $_POST['nama_barang'];
        $lokasi = $_POST['lokasi'];

        $model = new FileBarang();
        $model->kd_barang = $kd_barang;
        $model->nama_barang = $nama_barang;
        $model->lokasi = $lokasi;
        $model->harga_beli = 0;
        $model->harga_jual = 0;
        $model->stok = 0;
        $model->aktif = 1;

        $return['return'] = 0;

        if($model->save()){
            $update = KodeGenerate::find()->where('nama_alias = "BRG"')->one();

            $update->urutan = $update->urutan + 1;
            $update->save(false);

            $return['return'] = 1;
            $return['msg'] = 'sukses';
            $return['kdbarang'] = $model->kd_barang;
            $return['namabarang'] = $model->nama_barang;
        }else{
            $arrData = array();
            foreach($model->getErrors() as $key=>$val){
                $arrData[] = $val[0];
            }

            $return['msg'] = implode(', ',$arrData);
        }

        echo Json::encode($return);
    }

    public function actionSimpanpembelian(){
        $session = new Session;
        $session->open();

        $arr_data = $session['datapembelian'];

        if(isset($arr_data) && !empty($arr_data)){
            if($_POST['supplier'] == ''){
                $id_supplier = 1;
            }else{
                $id_supplier = $_POST['supplier'];
            }

            $model = new HeaderPembelian;
            $model->tgl_pembelian = date('Y-m-d', strtotime($_POST['tgl']));
            $model->keterangan = '-';
            $model->total_pembelian = 0;
            $model->id_supplier = $id_supplier;
            $return = array();
            if($model->save()){
                
               foreach($arr_data as $val){
                    $detail = new DetailPembelian;
                    $detail->id_pembelian = $model->id_pembelian;
                    $detail->kd_barang = $val['kodebarang'];
                    $detail->satuan = '-';
                    $detail->jumlah = $val['jumlah'];
                    $detail->harga_beli = $val['hargabeli'];
                    $detail->harga_jual = $val['hargajual'];
                    $detail->save();
                } 
                $return['error'] = 0;
                $return['redirect'] = Url::to(['transaksi/kelolapembelian']);
            }
        }else{
            $return['error'] = 1;
            $return['msg'] = "<p style='color:red;'><strong>ITEM PEMBELIAN TIDAK BOLEH KOSONG</strong></p>";
        }

        

        echo Json::encode($return);

    }

    public function actionAutocompletebarang($term){
        $data = FileBarang::find()
        ->select(['nama_barang as value', 'nama_barang as  label','kd_barang as id'])
        ->where(['like', 'nama_barang', $term])
        ->andWhere(['aktif'=>1])
        ->asArray()
        ->all();
 
        echo Json::encode($data);
    }

    public function actionAutocompletesupplier($term){
        $data = Supplier::find()
        ->select(['nama_supplier as value', 'nama_supplier as  label', 'id as id'])
        ->where(['like', 'nama_supplier', $term])
        ->asArray()
        ->all();
 
        echo Json::encode($data);
    }

    /**
     * Finds the HeaderPembelian model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HeaderPembelian the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HeaderPembelian::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
