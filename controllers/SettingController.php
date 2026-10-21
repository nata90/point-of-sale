<?php

namespace app\controllers;

use Yii;
use app\models\SettingApp;
use app\components\Utility;
use yii\helpers\Url;

class SettingController extends \yii\web\Controller
{
    public function actionIndex()
    {
    	$model = SettingApp::find()->one();

    	if($model == null){
    		$model = new SettingApp();
            $model->ip_address = Utility::getUserIP();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionSimpansetting(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

        $nama_app = Yii::$app->request->post('nama_app');
        $email = Yii::$app->request->post('email');
        $ip_addr = Yii::$app->request->post('ip_addr');
        $alamat = Yii::$app->request->post('alamat');
        $no_telp = Yii::$app->request->post('no_telp');

        $model = SettingApp::findOne(1);

        if($model == null)
            $model = new SettingApp();

        $model->app_name = $nama_app;
        $model->email = $email;
        if ($ip_addr !== null) {
            $model->ip_address = $ip_addr;
        }
        $model->alamat = $alamat;
        $model->no_telp = $no_telp;

        if($model->save()) {
            return 'sukses';
        } else {
            return json_encode($model->getErrors());
        }
    }

}
