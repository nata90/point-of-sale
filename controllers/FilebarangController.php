<?php

namespace app\controllers;

use Yii;
use app\models\FileBarang;
use app\models\FileBarangSearch;
use app\components\Utility;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\KodeGenerate;
use yii\filters\AccessControl;

/**
 * FilebarangController implements the CRUD actions for FileBarang model.
 */
class FilebarangController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','update','delete','create'],
                'rules' => [
                    [
                        'actions' => ['index','update','delete','create','autocompletebarang'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all FileBarang models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FileBarangSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider2 = $searchModel->searchStokKosong(Yii::$app->request->queryParams);
        $dataProvider3 = $searchModel->searchBarangED(Yii::$app->request->queryParams);
        $dataProvider4 = $searchModel->searchBeforeED(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProvider2'=>$dataProvider2,
            'dataProvider3'=>$dataProvider3,
            'dataProvider4'=>$dataProvider4
        ]);
    }

    /**
     * Displays a single FileBarang model.
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
     * Creates a new FileBarang model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FileBarang();

        //$kode_barang = Utility::generateKodeBarang();
        //$model->kd_barang = $kode_barang;
        $model->aktif = true;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $update = KodeGenerate::find()->where('nama_alias = "BRG"')->one();

            $update->urutan = $update->urutan + 1;
            $update->save(false);

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing FileBarang model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing FileBarang model.
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

    public function actionGetkodebarang(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $kode = Utility::generateKodeBarang();

        return ['kode'=>$kode];
    }

    /**
     * Finds the FileBarang model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FileBarang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FileBarang::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionAutocompletebarang($term){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $data = FileBarang::find()
        ->select(['nama_barang as value', 'CONCAT(nama_barang, " | ", CONCAT("Rp ", FORMAT(harga_jual, 0))) as  label','kd_barang as id'])
        ->where(['like','nama_barang', $term])
        ->andWhere(['aktif'=>1])
        ->asArray()
        ->all();

        return $data;
    }
}
