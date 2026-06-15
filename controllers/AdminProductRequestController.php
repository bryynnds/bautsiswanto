<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use app\models\Notification;
use app\models\ProductRequest;
use yii\data\Pagination;

class AdminProductRequestController extends Controller
{
    public function actionIndex()
    {
        $query = ProductRequest::find()
            ->joinWith(['user', 'jenisProduk']);

        $search = Yii::$app->request->get('search');
        $status = Yii::$app->request->get('status');
        $sort = Yii::$app->request->get('sort');

        // Search
        if (!empty($search)) {

            $query->andWhere([
                'or',

                ['like', 'product_requests.nama_produk', $search],

                ['like', 'user.username', $search],

                ['like', 'product_requests.status', $search],
            ]);
        }

        // Filter status
        if (!empty($status)) {

            $query->andWhere([
                'product_requests.status' => $status
            ]);
        }

        // Sorting tanggal
        switch ($sort) {

            case 'oldest':
                $query->orderBy([
                    'product_requests.created_at' => SORT_ASC
                ]);
                break;

            default:
                $query->orderBy([
                    'product_requests.created_at' => SORT_DESC
                ]);
        }

        $countQuery = clone $query;
        $totalRequests = $countQuery->count();

        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => 2,
        ]);

        $requests = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();


        if (Yii::$app->request->isAjax) {

            return $this->renderPartial(
                '/admin/product-request/_table',
                [
                    'requests' => $requests,
                    'pages' => $pages,
                ]
            );
        }

        return $this->render('/admin/product-request/index', [
            'requests' => $requests,
            'pages' => $pages,
            'totalRequests' => $totalRequests,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = ProductRequest::findOne($id);
        $oldStatus = $model->status;

        if (!$model) {
            throw new NotFoundHttpException('Request tidak ditemukan.');
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {

                $title = 'Update Request Produk';

                $message = '';

                if (
                    $model->status == 'diproses' &&
                    $oldStatus != 'diproses'
                ) {

                    $message = 'Request produk "' . $model->nama_produk . '" sedang diproses oleh admin.';

                    if (
                        $model->user &&
                        !empty($model->user->no_hp)
                    ) {

                        $waMessage =
                            "🔩 REQUEST PRODUK DIPROSES\n\n" .

                            "Halo {$model->user->username},\n\n" .

                            "Permintaan produk berikut sedang diproses oleh tim Baut Siswanto.\n\n" .

                            "Produk : {$model->nama_produk}\n" .

                            "Jenis : " . ($model->jenisProduk->nama ?? '-') . "\n\n" .

                            "Kami akan memberi kabar kembali setelah ada hasil pencarian produk.\n\n" .

                            "Terima kasih telah menggunakan layanan Baut Siswanto 🔩";

                        $nomor = preg_replace(
                            '/[^0-9]/',
                            '',
                            $model->user->no_hp
                        );

                        if (substr($nomor, 0, 1) == '0') {

                            $nomor = '62' . substr($nomor, 1);
                        }

                        Yii::$app->whatsapp->send(
                            $nomor,
                            $waMessage
                        );
                    }
                } elseif (
                    $model->status == 'tersedia' &&
                    $oldStatus != 'tersedia'
                ) {

                    $message = 'Produk "' . $model->nama_produk . '" sekarang sudah tersedia.';

                    if (
                        $model->user &&
                        !empty($model->user->no_hp)
                    ) {

                        $waMessage =
                            "🎉 PRODUK TERSEDIA\n\n" .

                            "Halo {$model->user->username},\n\n" .

                            "Produk yang Anda minta sekarang sudah tersedia.\n\n" .

                            "Produk : {$model->nama_produk}\n" .

                            "Jenis : " . ($model->jenisProduk->nama ?? '-') . "\n\n" .

                            "Silakan kunjungi website Baut Siswanto untuk melakukan pemesanan.\n\n" .

                            "Terima kasih telah menggunakan layanan Baut Siswanto 🔩";

                        $nomor = preg_replace(
                            '/[^0-9]/',
                            '',
                            $model->user->no_hp
                        );

                        if (substr($nomor, 0, 1) == '0') {

                            $nomor = '62' . substr($nomor, 1);
                        }

                        Yii::$app->whatsapp->send(
                            $nomor,
                            $waMessage
                        );
                    }
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