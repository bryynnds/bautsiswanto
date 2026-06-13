<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

$this->title = 'Laporan Penjualan';
?>

<div class="container mt-5">



    <div class="form-card mb-4">

        <h2 class="section-title mb-4">
            Laporan Penjualan
        </h2>
        <div class="filter-form">

            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['owner/report']
            ]); ?>

            <div class="filter-toolbar">

                <input type="text" name="keyword" class="filter-input" value="<?= $keyword ?>"
                    placeholder="Cari nama atau nomor HP...">

                <select name="status" class="filter-select">

                    <option value="">
                        Semua Status
                    </option>

                    <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>
                        Tertunda
                    </option>

                    <option value="paid" <?= $status == 'paid' ? 'selected' : '' ?>>
                        Sudah Dibayar
                    </option>

                    <option value="shipped" <?= $status == 'shipped' ? 'selected' : '' ?>>
                        Dikirim
                    </option>

                    <option value="completed" <?= $status == 'completed' ? 'selected' : '' ?>>
                        Selesai
                    </option>

                </select>

                <input type="date" name="start_date" class="filter-select" value="<?= $startDate ?>">

                <input type="date" name="end_date" class="filter-select" value="<?= $endDate ?>">

                <select name="sort" class="filter-select">

                    <option value="">
                        Tanggal Terbaru
                    </option>

                    <option value="oldest" <?= $sort == 'oldest' ? 'selected' : '' ?>>
                        Tanggal Terlama
                    </option>

                    <option value="highest" <?= $sort == 'highest' ? 'selected' : '' ?>>
                        Total Tertinggi
                    </option>

                    <option value="lowest" <?= $sort == 'lowest' ? 'selected' : '' ?>>
                        Total Terendah
                    </option>

                </select>

            </div>

            <div class="report-action mb-4">

                <button type="submit" class="btn btn-primary">

                    Filter

                </button>

                <?= Html::a(
                    'Reset',
                    ['owner/report'],
                    ['class' => 'btn btn-secondary']
                ) ?>

                <?= Html::a(
                    'Export PDF',
                    [
                        'owner/export-pdf',
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'status' => $status,
                        'keyword' => $keyword,
                        'sort' => $sort,
                    ],
                    [
                        'class' => 'btn btn-danger',
                        'target' => '_blank'
                    ]
                ) ?>

            </div>

            <?php ActiveForm::end(); ?>

        </div>

        <div class="request-info">

            Menampilkan

            <strong>
                <?= $dataProvider->getTotalCount() ?>
            </strong>

            transaksi

        </div>

        <div class="dashboard-card mt-4">

            <div class="table-responsive">

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
                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($dataProvider->models as $order): ?>

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
                                    <?= number_format(
                                        $order->total,
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>

                                <td>
                                    <?= $order->metode_pembayaran ?>
                                </td>

                                <td>

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

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

        <div class="pagination-wrapper">

            <?= LinkPager::widget([
                'pagination' => $dataProvider->pagination,
            ]) ?>

        </div>

    </div>

    <style>
        .request-info {
            background: #e0f7f6;
            color: #006666;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .report-action {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 15px;
            margin-right: 50px
        }

        .filter-toolbar {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
    </style>