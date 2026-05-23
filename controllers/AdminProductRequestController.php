<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use app\models\Notification;
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

                $title = 'Update Request Produk';

                $message = '';

                if ($model->status == 'diproses') {

                    $message = 'Request produk "' . $model->nama_produk . '" sedang diproses oleh admin.';
                } elseif ($model->status == 'tersedia') {

                    $message = 'Produk "' . $model->nama_produk . '" sekarang sudah tersedia.';
                } elseif ($model->status == 'tidak_ditemukan') {

                    $message = 'Produk "' . $model->nama_produk . '" tidak ditemukan di grosir.';
                } else {

                    $message = 'Status request produk diperbarui.';
                }

                $notification = new Notification();

                $notification->user_id = $model->user_id;
                $notification->title = $title;
                $notification->message = $message;

                $notification->save();

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