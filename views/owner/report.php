<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

$this->title = 'Laporan Penjualan';
?>

<div class="container mt-5">

    <h2 class="section-title mb-4">
        Laporan Penjualan
    </h2>

    <div class="form-card mb-4">

        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['owner/report']
        ]); ?>

        <div class="row g-3">

            <div class="col-md-4">

                <label>
                    Cari Nama / No HP
                </label>

                <input type="text" name="keyword" class="form-control" value="<?= $keyword ?>"
                    placeholder="Nama atau nomor HP...">

            </div>

            <div class="col-md-2">

                <label>
                    Status
                </label>

                <select name="status" class="form-control">

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

            </div>

            <div class="col-md-2">

                <label>
                    Tanggal Awal
                </label>

                <input type="date" name="start_date" class="form-control" value="<?= $startDate ?>">

            </div>

            <div class="col-md-2">

                <label>
                    Tanggal Akhir
                </label>

                <input type="date" name="end_date" class="form-control" value="<?= $endDate ?>">

            </div>

            <div class="col-md-2">

                <label>
                    Urutkan
                </label>

                <select name="sort" class="form-control">

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

            <div class="col-md-2 d-flex align-items-end">

                <button type="submit" class="btn btn-primary me-2">

                    Filter

                </button>

                <?= Html::a(
                    'Reset',
                    ['owner/report'],
                    ['class' => 'btn btn-secondary']
                ) ?>

            </div>
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
                    'class' => 'btn btn-danger ms-2',
                    'target' => '_blank'
                ]
            ) ?>

        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <div class="statistik-wrapper">

        <div class="statistik-card">
            <div class="stat-content">
                <span class="stat-label">
                    Total Pendapatan
                </span>

                <span class="stat-value">
                    Rp
                    <?= number_format($totalPendapatan ?? 0, 0, ',', '.') ?>
                </span>
            </div>
        </div>

        <div class="statistik-card">
            <div class="stat-content">
                <span class="stat-label">
                    Total Pesanan
                </span>

                <span class="stat-value">
                    <?= $totalPesanan ?>
                </span>
            </div>
        </div>

        <div class="statistik-card">
            <div class="stat-content">
                <span class="stat-label">
                    Total Pelanggan
                </span>

                <span class="stat-value">
                    <?= $totalPelanggan ?>
                </span>
            </div>
        </div>

    </div>

    <div class="dashboard-card mt-4">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'summary' => false,

            'columns' => [

                ['class' => 'yii\grid\SerialColumn'],

                'nama',

                'metode_pembayaran',

                [
                    'attribute' => 'status',
                    'value' => function ($model) {
                    return $model->statusLabel;
                }
                ],

                [
                    'attribute' => 'total',

                    'value' => function ($model) {

                    return 'Rp ' .
                        number_format(
                            $model->total,
                            0,
                            ',',
                            '.'
                        );
                }
                ],

                'created_at'
            ]
        ]) ?>

    </div>

</div>