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

$this->title = 'Dashboard Pemilik';
?>

<div class="container mt-5">

    <section class="dashboard">
        <div class="d-flex justify-content-between align-items-center mb-4">

            <h2 class="section-title mb-0">
                Dashboard Pemilik
            </h2>

            <form method="get" class="d-flex align-items-center">

                <label class="me-2 fw-bold">
                    Periode:
                </label>

                <input type="month" name="bulan" value="<?= $bulan ?>" class="form-control"
                    onchange="this.form.submit()" style="width: 180px;">

            </form>

        </div>
        <!-- Statistik Singkat -->
        <div class="statistik-wrapper">

            <!-- Baris 1 -->
            <div class="statistik-row top-row">

                <div class="statistik-card">
                    <div class="stat-icon">💰</div>

                    <div class="stat-content">
                        <span class="stat-label">
                            Total Pendapatan
                        </span>

                        <span class="stat-value">
                            Rp <?= number_format($totalPendapatan ?? 0, 0, ',', '.') ?>
                        </span>
                    </div>
                </div>

                <div class="statistik-card">
                    <div class="stat-icon">📈</div>

                    <div class="stat-content">
                        <span class="stat-label">
                            Pendapatan Bulan Ini
                        </span>

                        <span class="stat-value">
                            Rp <?= number_format($pendapatanBulanIni ?? 0, 0, ',', '.') ?>
                        </span>
                    </div>
                </div>

            </div>

            <!-- Baris 2 -->
            <div class="statistik-row bottom-row">

                <div class="statistik-card">
                    <div class="stat-icon">📦</div>

                    <div class="stat-content">
                        <span class="stat-label">
                            Total Produk
                        </span>

                        <span class="stat-value">
                            <?= $totalProduk ?>
                        </span>
                    </div>
                </div>

                <div class="statistik-card">
                    <div class="stat-icon">👥</div>

                    <div class="stat-content">
                        <span class="stat-label">
                            Total Pelanggan
                        </span>

                        <span class="stat-value">
                            <?= $jumlahCustomer ?>
                        </span>
                    </div>
                </div>

                <div class="statistik-card">
                    <div class="stat-icon">🛒</div>

                    <div class="stat-content">
                        <span class="stat-label">
                            Total Pesanan
                        </span>

                        <span class="stat-value">
                            <?= $totalOrder ?>
                        </span>
                    </div>
                </div>

            </div>

        </div>


        <h3>
            Top 5 Produk Terlaris
        </h3>
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

        <!-- Grafik -->
        <div class="graphic-wrapper">
            <div class="graphic-card">
                <h3>
                    Grafik Produk Terlaris
                </h3>
                <canvas id="pieChart"></canvas>
            </div>

            <div class="graphic-card">
                <h3>
                    Grafik Penjualan Harian
                </h3>
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
                        text: 'Tanggal'
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