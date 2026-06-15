<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\ForgotPasswordForm;
use app\models\ResetPasswordForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\HomepageHero;
use app\models\HomepageProduk;
use app\models\HomepageKeunggulan;
use app\models\HomepageTestimoni;
use app\models\HomepagePromo;
use app\models\JenisProduk;

use app\models\HomepageProdukTerlaris;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
                'class' => VerbFilter::class,
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
        $hero = HomepageHero::find()->one(); // karena hanya 1 baris
        $produks = HomepageProduk::find()->all();
        $keunggulans = HomepageKeunggulan::find()->all();

        if (Yii::$app->request->get('success')) {
            Yii::$app->session->setFlash('success', 'Pembayaran berhasil!');
        }
        if (Yii::$app->request->get('pending')) {
            Yii::$app->session->setFlash('info', 'Menunggu konfirmasi pembayaran.');
        }
        if (Yii::$app->request->get('error')) {
            Yii::$app->session->setFlash('danger', 'Pembayaran gagal.');
        }

        $configTerlaris = HomepageProdukTerlaris::find()->one();

        $jumlahTampil = $configTerlaris
            ? $configTerlaris->jumlah_tampil
            : 3;


        $produkTerlaris = (new \yii\db\Query())
            ->select([
                'p.id',
                'p.title',
                'p.harga_kg',
                'p.harga_bijian',
                'p.image',
                'p.description',
                'SUM(oi.qty) AS jumlah_terjual'
            ])
            ->from(['oi' => 'order_items'])
            ->innerJoin(['p' => 'homepage_produk'], 'oi.produk_id = p.id')
            ->groupBy([
                'p.id',
                'p.title',
                'p.harga_kg',
                'p.harga_bijian',
                'p.image',
                'p.description',
            ])
            ->orderBy(['jumlah_terjual' => SORT_DESC])
            ->limit($jumlahTampil)
            ->all();

        $jenisProduks = JenisProduk::find()->all();


        return $this->render('index', [
            'hero' => $hero,
            'produks' => $produks,
            'keunggulans' => $keunggulans,
            'produkTerlaris' => $produkTerlaris,
            'jenisProduks' => $jenisProduks,
            'configTerlaris' => $configTerlaris,
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

            $user = Yii::$app->user->identity;

            if ($user->isOwner()) {
                return $this->redirect(['owner/dashboard']);
            }

            if ($user->isAdmin()) {
                return $this->redirect(['admin/dashboard']);
            }

            return $this->goHome();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionForgotPassword()
    {
        $model = new ForgotPasswordForm();

        if (
            $model->load(Yii::$app->request->post())
            && $model->validate()
        ) {

            if ($model->sendResetEmail()) {

                Yii::$app->session->setFlash(
                    'success',
                    'Link reset password telah dikirim ke email Anda.'
                );

            } else {

                Yii::$app->session->setFlash(
                    'error',
                    'Email tidak ditemukan.'
                );
            }

            return $this->redirect(['site/login']);

        }

        return $this->render('account/forgot-password', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        $user = User::findOne([
            'reset_token' => $token
        ]);

        if (!$user) {

            Yii::$app->session->setFlash(
                'error',
                'Link reset password tidak valid.'
            );

            return $this->redirect(['site/login']);
        }

        if (
            strtotime($user->reset_token_expired_at)
            < time()
        ) {

            Yii::$app->session->setFlash(
                'error',
                'Link reset password telah kedaluwarsa.'
            );

            return $this->redirect(['site/forgot-password']);
        }

        if (!$user) {

            Yii::$app->session->setFlash(
                'error',
                'Link reset password tidak valid.'
            );

            return $this->redirect(['site/login']);
        }

        $model = new ResetPasswordForm();

        if (
            $model->load(Yii::$app->request->post())
            && $model->validate()
        ) {

            $user->setPassword($model->password);

            $user->reset_token = null;
            $user->reset_token_expired_at = null;

            $user->save(false);

            Yii::$app->session->setFlash(
                'success',
                'Password berhasil diubah.'
            );

            return $this->redirect(['site/login']);
        }

        return $this->render('account/reset-password', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {
        $model = new \app\models\SignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {

            Yii::$app->session->setFlash(
                'success',
                'Pendaftaran berhasil. Silakan cek email untuk verifikasi akun.'
            );

            return $this->redirect(['site/login']);
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionVerifyEmail($token)
    {
        $user = User::findOne([
            'verification_token' => $token
        ]);

        if (!$user) {

            Yii::$app->session->setFlash(
                'warning',
                'Link verifikasi sudah digunakan atau tidak valid.'
            );

            return $this->redirect(['site/login']);
        }

        $user->is_verified = 1;
        $user->verification_token = null;

        $user->save(false);

        Yii::$app->session->setFlash(
            'success',
            'Email berhasil diverifikasi. Silakan login.'
        );

        return $this->redirect(['site/login']);
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
}
