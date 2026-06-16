<?php

use yii\grid\GridView;
use yii\helpers\Html;
use app\models\HomepageProduk;
use yii\data\ArrayDataProvider;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\HomepageProduk[] $produk */
/** @var yii\data\ActiveDataProvider $orderDataProvider */
/** @var int $jumlahCustomer */
/** @var int $totalProduk */
/** @var int $totalOrder */
/** @var array $produkTerlaris */
/** @var array $produkTerlarisGrafik */
/** @var array $pieLabels */
/** @var array $pieData */
/** @var array $bulanLabels */
/** @var array $bulanData */

$this->title = 'Dashboard Admin';
?>

<div class="container mt-5">

    <section class="dashboard">
        <!-- Statistik Singkat -->
        <div class="statistik-wrapper">

            <!-- Baris 1 -->
            <div class="statistik-row top-row">

                <div class="statistik-card">
                    <div class="stat-icon">📦</div>

                    <div class="stat-content">
                        <span class="stat-label">Total Produk</span>
                        <span class="stat-value"><?= $totalProduk ?></span>
                    </div>
                </div>

                <div class="statistik-card">
                    <div class="stat-icon">👤</div>

                    <div class="stat-content">
                        <span class="stat-label">Total Pelanggan</span>
                        <span class="stat-value">
                            <?= $jumlahCustomer ?>
                        </span>
                    </div>
                </div>



            </div>

            <!-- Baris 2 -->
            <div class="statistik-row bottom-row-admin">

                <div class="statistik-card">
                    <div class="stat-icon">🛒</div>

                    <div class="stat-content">
                        <span class="stat-label">Pesanan Aktif</span>
                        <span class="stat-value">
                            <?= $totalPesananAktif ?>
                        </span>
                    </div>
                </div>

                <div class="statistik-card">
                    <div class="stat-icon">✅</div>

                    <div class="stat-content">
                        <span class="stat-label">Pesanan Selesai</span>
                        <span class="stat-value">
                            <?= $totalPesananSelesai ?>
                        </span>
                    </div>
                </div>

                <div class="statistik-card">
                    <div class="stat-icon">📋</div>

                    <div class="stat-content">
                        <span class="stat-label">Permintaan Aktif</span>
                        <span class="stat-value">
                            <?= $totalRequestAktif ?>
                        </span>
                    </div>
                </div>

                <div class="statistik-card">
                    <div class="stat-icon">📄</div>

                    <div class="stat-content">
                        <span class="stat-label">Permintaan Selesai</span>
                        <span class="stat-value">
                            <?= $totalRequestSelesai ?>
                        </span>
                    </div>
                </div>

            </div>

        </div>

        <h3>Produk Terlaris</h3>
        <div class="produkunggulan-card">
            <div class="produk-grid">
                <?php if (!empty($produkTerlaris)): ?>
                    <?php foreach ($produkTerlaris as $p): ?>
                        <div class="produk-card">
                            <?php if ($p['image']): ?>
                                <img src="<?= Yii::getAlias('@web') ?>/<?= ($p['image']) ?>" alt="<?= ($p['title']) ?>"
                                    class="produk-img">
                            <?php endif; ?>

                            <h4><?= Html::encode($p['title']) ?></h4>

                            <div class="produk-terjual">
                                Terjual <?= $p['jumlah_terjual'] ?> kali
                            </div>

                            <p class="harga">
                                Rp <?= number_format($p['harga_bijian'], 0, ',', '.') ?>/biji
                            </p>

                            <p class="harga">
                                Rp <?= number_format($p['harga_kg'], 0, ',', '.') ?>/kg
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-request">
                        <div class="empty-icon">📊</div>
                        <h4>Belum Ada Data Penjualan</h4>
                        <p>Produk terlaris akan muncul setelah ada transaksi.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <h3>Daftar Produk</h3>
        <div class="dashboard-card" id="produk-section">
            <?php Pjax::begin([
                'id' => 'produk-grid',
                'enablePushState' => false,
                'timeout' => 0,
            ]); ?>
            <div class="table-responsive" id="produkTable">

                <?= GridView::widget([
                    'layout' => '{items}',
                    'summary' => false,
                    'dataProvider' => $produkDataProvider,
                    'options' => [
                        'class' => 'gridview-wrapper'
                    ],
                    'tableOptions' => ['class' => 'cart-table'],
                    'columns' => [
                        [
                            'label' => 'Gambar',
                            'format' => 'raw',
                            'value' => function ($model) {
                                    return Html::img(
                                        Yii::getAlias('@web') . '/' . $model->image,
                                        ['class' => 'cart-img']
                                    );
                                }
                        ],
                        'title',
                        'description',
                        [
                            'label' => 'Harga',
                            'format' => 'raw',
                            'value' => function ($model) {
                                    return
                                        '<p>Kiloan: Rp ' . number_format($model->harga_kg, 0, ',', '.') . '</p>' .
                                        '<p>Bijian : Rp ' . number_format($model->harga_bijian, 0, ',', '.') . '</p>';
                                }
                        ],
                    ],
                ]); ?>

            </div>
            <div class="pagination-wrapper produk-pagination">
                <?= \yii\widgets\LinkPager::widget([
                    'pagination' => $produkDataProvider->pagination,
                    'maxButtonCount' => 3,
                    'linkOptions' => [
                        'data-pjax' => 1,
                    ],
                ]) ?>
            </div>
            <?php Pjax::end(); ?>
        </div>


        <h3>Daftar Pesanan</h3>
        <div class="dashboard-card" id="order-section">
            <?php Pjax::begin([
                'id' => 'order-grid',
                'enablePushState' => false,
                'timeout' => 0,
            ]); ?>
            <div class="table-responsive" id="orderTable">

                <?= GridView::widget([
                    'summary' => false,
                    'layout' => '{items}',
                    'options' => [
                        'class' => 'gridview-wrapper'
                    ],
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

            <div class="pagination-wrapper order-pagination">
                <?= \yii\widgets\LinkPager::widget([
                    'pagination' => $orderDataProvider->pagination,
                    'maxButtonCount' => 3,
                    'linkOptions' => [
                        'data-pjax' => 1,
                    ],
                ]) ?>
            </div>

            <?php Pjax::end(); ?>
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
        type: 'bar',
        data: {
            labels: <?= json_encode($pieLabels) ?>,
            datasets: [{
                label: 'Jumlah Terjual',
                data: <?= json_encode($pieData) ?>,
                backgroundColor: '#0f766e',
                borderRadius: 8
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false
                }
            },

            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Produk Terjual (x)'
                    }
                },
                y: {
                    title: {
                        display: false,
                        text: 'Nama Produk'
                    }
                }
            }
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
                tension: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return 'Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                },
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Periode Bulan'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Total Penjualan (Rp)'
                    }
                }
            }
        }
    });

    $(document).on('pjax:send', function () {
        console.log('PJAX SEND');
    });

    $(document).on('pjax:success', function () {
        console.log('PJAX SUCCESS');
    });

    $(document).on(
        'click',
        '.pagination a',
        function (e) {

            e.preventDefault();

            $.ajax({

                url: $(this).attr('href'),

                success: function (response) {

                    $('#orderTable', '#produkTable').html(response);

                }

            });

        }
    );

    $(document).on('pjax:end', function (event) {
        const container = event.target.id;

        if (container === 'produk-grid') {
            document.getElementById('produk-section')
                .scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
        }

        if (container === 'order-grid') {
            document.getElementById('pesanan-section')
                .scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
        }
    });
</script>