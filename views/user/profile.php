<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Profil Saya';
?>

<style>
    /* ====== STYLE CARD ====== */
    .dashboard-card {
        background: #fff;
        padding: 20px;
        margin-bottom: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    /* ====== TABEL STYLE ====== */
    .cart-table {
        width: 100%;
        border-collapse: collapse;
    }

    .cart-table th {
        background: #f8f9fa;
        padding: 10px;
        font-weight: 600;
        text-align: center;
        border-bottom: 2px solid #dee2e6;
    }

    .cart-table td {
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .cart-table tr:hover {
        background: #f5f7f9;
    }

    /* Tombol detail */
    .btn-detail {
        padding: 5px 12px;
        border-radius: 6px;
    }

    .order-summary-card {
        margin-top: 15px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 16px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .summary-row span {
        color: #6c757d;
    }

    .summary-row strong {
        font-weight: 600;
    }

    .total-row {
        font-size: 1.15rem;
    }

    .total-row strong {
        color: #198754;
        font-size: 1.2rem;
    }
</style>

<div class="container mt-5">

    <!-- CARD PROFIL BARU -->
    <div class="form-card mt-4">
        <h2 class="section-title mb-3 text-center">Profil Pengguna</h2>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label><strong>Username</strong></label>
                <div class="form-control bg-light"><?= Html::encode($user->username) ?></div>
            </div>

            <div class="col-md-6 mb-3">
                <label><strong>Password</strong></label>
                <div class="form-control bg-light">******</div>
            </div>
        </div>

        <div class="text-center mt-3">
            <?= Html::a('Ubah Password', ['user/update'], [
                'class' => 'btn btn-primary px-4'
            ]) ?>
        </div>
    </div>


    <!-- CARD RIWAYAT PEMESANAN -->
    <div class="dashboard-card">
        <h3>Riwayat Pemesanan</h3>
        <div class="table-responsive mt-4">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>No HP</th>
                        <th>Alamat</th>
                        <th>Metode</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $order->nama ?></td>
                            <td><?= $order->no_hp ?></td>
                            <td><?= $order->alamat ?></td>
                            <td><?= $order->metode_pembayaran ?></td>
                            <td>Rp <?= number_format($order->total) ?></td>
                            <td><?= $order->created_at ?></td>
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
                                <button class="btn-detail-order" data-id="<?= $order->id ?>">
                                    Lihat
                                </button>

                                <?php if ($order->status === 'shipped'): ?>

                                    <a href="<?= Url::to([
                                        'user/pesanan-diterima',
                                        'id' => $order->id
                                    ]) ?>" class="btn btn-success btn-sm">

                                        Pesanan Diterima
                                    </a>



                                <?php endif; ?>

                                <?php if (
                                    $order->status === 'Pending'
                                    && $order->metode_pembayaran === 'Transfer Bank'
                                ): ?>

                                    <a href="<?= Url::to([
                                        'checkout/repay',
                                        'id' => $order->id
                                    ]) ?>" class="btn btn-warning btn-sm mt-1">

                                        Bayar Lagi
                                    </a>

                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Detail Pesanan -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <table class="cart-table" id="itemsTable">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div id="order-summary">
                </div>
                <div class="text-end mt-3" id="repay-container">
                </div>

            </div>
        </div>
    </div>
</div>

<?php

$ajaxUrl = Url::to(['user/order-items']);

$js = <<<JS
$(".btn-detail-order").on("click", function() {

    var orderId = $(this).data("id");

    $.getJSON("$ajaxUrl", { id: orderId }, function(data) {

        var tbody = $("#itemsTable tbody");
        tbody.empty();

        data.items.forEach(function(item) {

            tbody.append(`
                <tr>
                    <td>\${item.nama_produk}</td>
                    <td>\${item.qty}</td>
                    <td>Rp \${Number(item.harga).toLocaleString()}</td>
                    <td>Rp \${Number(item.subtotal).toLocaleString()}</td>
                </tr>
            `);
        });

        let tombol = '';

        if (
            data.order.status === 'pending' &&
            data.order.metode === 'Transfer Bank'
        ) {

            tombol = `
                <a href="/checkout/repay?id=\${data.order.id}"
                   class="btn btn-warning">

                   Bayar Lagi
                </a>
            `;
        }

        let summaryHtml = `

<div class="order-summary-card">

    <div class="summary-row">
        <span>Kurir</span>
        <strong>
            \${data.order.courier
                ? data.order.courier.toUpperCase()
                : '-'}
        </strong>
    </div>

    <div class="summary-row">
        <span>Nomor Resi</span>
        <strong>
            \${data.order.tracking_number
                ? data.order.tracking_number
                : '-'}
        </strong>
    </div>

    <div class="summary-row">
        <span>Subtotal Produk</span>
        <strong>
            Rp \${Number(data.order.subtotal_produk)
                .toLocaleString('id-ID')}
        </strong>
    </div>

    <div class="summary-row">
        <span>Ongkir</span>
        <strong>
            Rp \${Number(data.order.shipping_cost)
                .toLocaleString('id-ID')}
        </strong>
    </div>

</div>

<div class="order-total-modern">

    <span>Total Bayar</span>

    <strong>
        Rp \${Number(data.order.total)
            .toLocaleString('id-ID')}
    </strong>

</div>

`;

$('#order-summary').html(summaryHtml);

        $('#repay-container').html(tombol);

        $("#detailModal").modal("show");
    });
});
JS;

$this->registerJs($js);
?>