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
        $nama_app = $_POST['nama_app'];
        $email = $_POST['email'];
        $ip_addr = $_POST['ip_addr'];

        $model = SettingApp::findOne(1);

        if($model == null)
            $model = new SettingApp();

        $model->app_name = $nama_app;
        $model->email = $email;
        $model->ip_address = $ip_addr;

        if($model->save()) {
            echo 'sukses';
        }

    }

}
