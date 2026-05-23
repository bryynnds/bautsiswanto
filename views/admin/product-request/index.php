<?php

use yii\helpers\Html;
?>

<div class="container mt-4">

    <h2 class="mb-4">Request Produk User</h2>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered">

        <thead>
            <tr>
                <th>User</th>
                <th>Produk</th>
                <th>Jenis</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

        <?php foreach ($requests as $request): ?>

            <tr>

                <td>
                    <?= Html::encode($request->user->username ?? '-') ?>
                </td>

                <td>
                    <?= Html::encode($request->nama_produk) ?>
                </td>

                <td>
                    <?= $request->jenisProduk
                        ? Html::encode($request->jenisProduk->nama_jenis)
                        : '-' ?>
                </td>

                <td>
                    <?= Html::encode($request->status) ?>
                </td>

                <td>
                    <?= date(
                        'd M Y H:i',
                        strtotime($request->created_at)
                    ) ?>
                </td>

                <td>
                    <?= Html::a(
                        'Proses',
                        ['update', 'id' => $request->id],
                        ['class' => 'btn btn-primary btn-sm']
                    ) ?>
                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

</div>