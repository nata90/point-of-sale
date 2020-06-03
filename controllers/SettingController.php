<?php

namespace app\controllers;

use Yii;
use app\models\SettingApp;
use yii\helpers\Url;

class SettingController extends \yii\web\Controller
{
    public function actionIndex()
    {
    	$model = SettingApp::find()->one();

    	if($model == null)
    		$model = new SettingApp();

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

        $model = SettingApp::find()->one();

        if($model == null)
            $model = new SettingApp();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            echo 'sukses';
        }

    }

}
