<?php

use yii\helpers\Html;
use app\models\JenisProduk;
?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Riwayat Request Produk</h2>

        <?= Html::a(
            'Tambah Request',
            ['create'],
            ['class' => 'btn btn-success']
        ) ?>
    </div>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered">

        <thead>
            <tr>
                <th>Produk</th>
                <th>Jenis</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($requests as $request): ?>

                <tr>
                    <td><?= Html::encode($request->nama_produk) ?></td>

                    <td>
                        <?= $request->jenisProduk ? Html::encode($request->jenisProduk->nama_jenis) : '-' ?>
                    </td>

                    <td>
                        <span class="badge bg-secondary">
                            <?= $request->status ?>
                        </span>
                    </td>

                    <td>
                            <?= date('d M Y H:i', strtotime($request->created_at)) ?>
                    </td>
                </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

</div>