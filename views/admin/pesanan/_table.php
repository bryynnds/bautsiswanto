<?php
use yii\widgets\LinkPager;
use yii\helpers\Html;
?>

<table class="cart-table">

    <thead>
        <tr>
            <th>ID</th>
            <th>Pembeli</th>
            <th>No HP</th>
            <th>Total</th>
            <th>Metode</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>

        <?php foreach ($orders as $order): ?>

            <tr>

                <td>
                    <?= $order->id ?>
                </td>

                <td>
                    <?= Html::encode($order->nama) ?>
                </td>

                <td>
                    <?= Html::encode($order->no_hp) ?>
                </td>

                <td>
                    Rp
                    <?= number_format($order->total, 0, ',', '.') ?>
                </td>

                <td>
                    <?= $order->metode_pembayaran ?>
                </td>

                <td>

                    <?php

                    $statusLabel = [
                        'pending' => 'Tertunda',
                        'paid' => 'Sudah Dibayar',
                        'shipped' => 'Dikirim',
                        'completed' => 'Selesai',
                        'failed' => 'Gagal',
                        'cancelled' => 'Dibatalkan',
                    ];

                    $statusClass = [
                        'pending' => 'status-pending',
                        'paid' => 'status-paid',
                        'shipped' => 'status-shipped',
                        'completed' => 'status-completed',
                        'failed' => 'status-failed',
                        'cancelled' => 'status-cancelled',
                    ];

                    ?>

                    <span class="<?= $statusClass[$order->status] ?? 'status-default' ?>">

                        <?= $statusLabel[$order->status] ?? $order->status ?>

                    </span>

                </td>

                <td>
                    <?= Yii::$app->formatter->asDatetime(
                        $order->created_at,
                        'php:d-m-Y H:i'
                    ) ?>
                </td>
                <td>
                    <button class="btn-detail-order" data-id="<?= $order->id ?>">

                        Detail
                    </button>
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