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
use app\components\Utility;
use yii\helpers\Json;

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
            return $this->goBack();
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

        $file_barang = FileBarang::find()->where(['kd_barang'=>$kd_barang])->one();
        $total = $file_barang->harga_jual * $qty;

        $table = '<tr>
            <td>1.</td>
            <td>'.$file_barang->nama_barang.'</td>
            <td>'.Utility::rupiah($file_barang->harga_jual).'</td>
            <td>'.$qty.'</td>
            <td>'.Utility::rupiah($total).'</td>
            <td style="text-align:center;"><a href="#" title="Delete"><i class="fa fa-trash"></i></a></td>
        </tr>';
                            

        $arr_data['data'] = $table;

        echo Json::encode($arr_data);

    }
}
