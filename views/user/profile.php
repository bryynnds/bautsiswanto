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
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
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
                        <td><?= $order->status ?></td>
                        <td>
                            <button class="btn btn-info btn-detail" data-id="<?= $order->id ?>">
                                Lihat
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- Modal Detail Pesanan -->
<div class="modal fade" id="orderItemsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Order</h5>
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

            </div>
        </div>
    </div>
</div>

<?php

$ajaxUrl = Url::to(['user/order-items']);

$js = <<<JS
$(".btn-detail").on("click", function() {
    var orderId = $(this).data("id");

    $.getJSON("$ajaxUrl", { id: orderId }, function(data) {

        var tbody = $("#itemsTable tbody");
        tbody.empty();

        data.forEach(function(item) {
            tbody.append(`
                <tr>
                    <td>\${item.nama_produk}</td>
                    <td>\${item.qty}</td>
                    <td>Rp \${Number(item.harga).toLocaleString()}</td>
                    <td>Rp \${Number(item.subtotal).toLocaleString()}</td>
                </tr>
            `);
        });

        $("#orderItemsModal").modal("show");
    });
});
JS;

$this->registerJs($js);
?>
