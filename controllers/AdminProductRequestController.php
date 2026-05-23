<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use app\models\ProductRequest;

class AdminProductRequestController extends Controller
{
    public function actionIndex()
    {
        $requests = ProductRequest::find()
            ->orderBy(['id' => SORT_DESC])
            ->all();

        return $this->render('/admin/product-request/index', [
            'requests' => $requests,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = ProductRequest::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('Request tidak ditemukan.');
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {

                Yii::$app->session->setFlash(
                    'success',
                    'Request berhasil diperbarui.'
                );

                return $this->redirect(['index']);
            }
        }

        return $this->render('/admin/product-request/update', [
            'model' => $model,
        ]);
    }
}