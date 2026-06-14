<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
?>

<table class="request-table-admin">

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
                    <?= Html::encode(
                        $request->user->username ?? '-'
                    ) ?>
                </td>

                <td>
                    <strong>
                        <?= Html::encode(
                            $request->nama_produk
                        ) ?>
                    </strong>
                </td>

                <td>
                    <?= $request->jenisProduk
                        ? Html::encode(
                            $request->jenisProduk->nama_jenis
                        )
                        : '-' ?>
                </td>

                <td>

                    <?php
                    $status = strtolower($request->status);

                    if ($status === 'tersedia') {
                        $class = 'status-success';
                    } elseif ($status === 'pending') {
                        $class = 'status-warning';
                    } elseif ($status === 'ditolak') {
                        $class = 'status-danger';
                    } else {
                        $class = 'status-primary';
                    }
                    ?>

                    <span class="status-badge <?= $class ?>">
                        <?= ucfirst($request->status) ?>
                    </span>

                </td>

                <td>
                    <?= date(
                        'd-m-Y H:i',
                        strtotime($request->created_at)
                    ) ?>
                </td>

                <td>

                    <?= Html::a(
                        'Proses',
                        ['update', 'id' => $request->id],
                        [
                            'class' => 'btn btn-primary btn-sm'
                        ]
                    ) ?>

                </td>

            </tr>

        <?php endforeach; ?>

    </tbody>

</table>

<div class="pagination-wrapper">

    <?= LinkPager::widget([
        'pagination' => $pages,
    ]) ?>

</div>