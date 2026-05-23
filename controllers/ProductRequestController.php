<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

use app\models\ProductRequest;

class ProductRequestController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'index'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
        $model = new ProductRequest();

        if ($model->load(Yii::$app->request->post())) {

            $model->user_id = Yii::$app->user->id;
            $model->status = ProductRequest::STATUS_PENDING;

            $uploadedFile = UploadedFile::getInstance($model, 'foto');

            if ($uploadedFile) {

                $fileName = uniqid() . '.' . $uploadedFile->extension;

                $uploadPath = Yii::getAlias('@webroot/uploads/request-products/');

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $uploadedFile->saveAs($uploadPath . $fileName);

                $model->foto = $fileName;
            }

            if ($model->save()) {

                Yii::$app->session->setFlash(
                    'success',
                    'Request produk berhasil dikirim.'
                );

                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionIndex()
    {
        $requests = ProductRequest::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->orderBy(['id' => SORT_DESC])
            ->all();

        return $this->render('index', [
            'requests' => $requests,
        ]);
    }
}