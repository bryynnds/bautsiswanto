<?php

use yii\grid\GridView;
use yii\helpers\Html;
use app\models\HomepageProduk;
use yii\data\ArrayDataProvider;

/** @var yii\web\View $this */
/** @var app\models\HomepageProduk[] $produk */
/** @var yii\data\ActiveDataProvider $orderDataProvider */
/** @var int $jumlahCustomer */
/** @var int $totalProduk */
/** @var int $totalOrder */
/** @var array $produkTerlaris */
/** @var array $pieLabels */
/** @var array $pieData */
/** @var array $bulanLabels */
/** @var array $bulanData */

$dataProvider = new ArrayDataProvider([
    'allModels' => $produk,
    'pagination' => false, // kalau mau disable pagination
]);

$this->title = 'Dashboard Admin';
?>

<div class="container mt-5">

    <section class="dashboard">
        <!-- <h2><?= Html::encode($this->title) ?></h2> -->
        <!-- Statistik Singkat -->
        <div class="statistik-wrapper">
            <div class="statistik-card">
                <h3>Total Produk</h3>
                <p class="angka"><?= $totalProduk ?></p>
            </div>
            <div class="statistik-card">
                <h3>Total Order</h3>
                <p class="angka"><?= $totalOrder ?></p>
            </div>
            <div class="statistik-card">
                <h3>Total Customer</h3>
                <p class="angka"><?= $jumlahCustomer ?></p>
            </div>
        </div>

        <h3>Produk Terlaris</h3>
        <div class="produkunggulan-card">
            <div class="produk-grid">
                <?php if (!empty($produkTerlaris)): ?>
                    <?php foreach ($produkTerlaris as $p): ?>
                        <div class="produk-card">
                            <h4><?= Html::encode($p['title']) ?></h4>
                            <?php if ($p['image']): ?>
                                <img src="<?= Yii::getAlias('@web') ?>/<?= ($p['image']) ?>" alt="<?= ($p['title']) ?>" class="produk-img">
                            <?php endif; ?>

                            <p class="harga">Harga per Kg : Rp <?= number_format($p['harga_kg'], 0, ',', '.') ?></p>
                            <p class="harga">Harga per Biji : Rp <?= number_format($p['harga_bijian'], 0, ',', '.') ?></p>
                            <p>Terjual: <strong><?= $p['jumlah_terjual'] ?></strong></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Belum ada data penjualan.</p>
                <?php endif; ?>
            </div>
        </div>

        <h3>Daftar Produk</h3>
        <div class="dashboard-card">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Deskripsi</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produk as $p): ?>
                        <tr>
                            <td>
                                <img src="<?= Yii::getAlias('@web') ?>/<?= $p->image ?>" alt="<?= $p->title ?>" class="cart-img">
                            </td>
                            <td><?= Html::encode($p->title) ?></td>
                            <td><?= Html::encode($p->description) ?></td>
                            <td>
                                <p>Kiloan: Rp <?= number_format($p->harga_kg, 0, ',', '.') ?></p>
                                <p>Bijian : Rp <?= number_format($p->harga_bijian, 0, ',', '.') ?></p>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


        <h3>Daftar Order</h3>
        <div class="dashboard-card">
            <?= GridView::widget([
                'summary' => false,
                'dataProvider' => $orderDataProvider,
                'tableOptions' => ['class' => 'cart-table'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'nama',
                    'no_hp',
                    'alamat:ntext',
                    'metode_pembayaran',
                    [
                        'attribute' => 'total',
                        'value' => function ($model) {
                            return 'Rp ' . number_format($model->total, 0, ',', '.');
                        }
                    ],
                    'created_at',
                ],
            ]); ?>
        </div>

        <!-- Grafik -->
        <div class="graphic-wrapper">
            <div class="graphic-card">
                <h3>Grafik Produk Terlaris</h3>
                <canvas id="pieChart"></canvas>
            </div>

            <div class="graphic-card">
                <h3>Grafik Penjualan Per Bulan</h3>
                <canvas id="lineChart"></canvas>
            </div>
        </div>

    </section>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const pieChart = new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: <?= json_encode($pieLabels) ?>,
            datasets: [{
                data: <?= json_encode($pieData) ?>,
                backgroundColor: ['#f87171', '#60a5fa', '#34d399', '#fbbf24', '#a78bfa'],
            }]
        }
    });

    const lineChart = new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: <?= json_encode($bulanLabels) ?>,
            datasets: [{
                label: 'Total Penjualan',
                data: <?= json_encode($bulanData) ?>,
                borderColor: '#006666',
                backgroundColor: '#008b8b',
                fill: false,
                tension: 0.3
            }]
        }
    });
</script>