<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>

<table class="request-table">

    <thead>
        <tr class="request-row">
            <th>Produk</th>
            <th>Jenis</th>
            <th>Keterangan</th>
            <th>Status</th>
            <th>Tanggal</th>
        </tr>
    </thead>

    <tbody>

        <?php foreach ($requests as $request): ?>

            <?php

            $status = strtolower($request->status);

            if ($status === 'tersedia') {
                $class = 'status-success';
            } elseif ($status === 'pending') {
                $class = 'status-warning';
            } elseif ($status === 'tidak_ditemukan') {
                $class = 'status-danger';
            } else {
                $class = 'status-primary';
            }

            ?>

            <tr class="request-row">

                <td data-label="Produk">
                    <strong>
                        <?= Html::encode($request->nama_produk) ?>
                    </strong>
                </td>

                <td data-label="Jenis">
                    <?= $request->jenisProduk
                        ? Html::encode($request->jenisProduk->nama_jenis)
                        : '-' ?>
                </td>

                <td data-label="Keterangan">
                    <?= $request->keterangan
                        ? Html::encode($request->keterangan)
                        : '-' ?>
                </td>

                <td data-label="Status">

                    <span class="status-badge <?= $class ?>">

                        <?= ucfirst(
                            str_replace('_', ' ', $request->status)
                        ) ?>

                    </span>

                </td>

                <td data-label="Tanggal">
                    <?= date(
                        'd-m-Y H:i',
                        strtotime($request->created_at)
                    ) ?>
                </td>

            </tr>

        <?php endforeach; ?>

    </tbody>

</table>

<div class="pagination-wrapper">

    <?= LinkPager::widget([
        'pagination' => $pages,
        'maxButtonCount' => 3,
    ]) ?>

</div>