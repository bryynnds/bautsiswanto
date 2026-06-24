<?php

use yii\helpers\Html;

$this->title = 'Daftar Pesanan';
?>

<div class="container mt-5">

    <div class="dashboard-card">
        <h2 class="section-title mb-3">
            Daftar Pesanan
        </h2>

        <form id="filterForm">
            <div class="filter-toolbar">

                <input type="text" id="searchOrder" name="search" class="filter-input"
                    placeholder="Cari ID, pembeli, status...">

                <select name="status" id="statusFilter" class="filter-select">

                    <option value="">
                        Semua Status
                    </option>

                    <option value="pending">
                        Tertunda
                    </option>

                    <option value="paid">
                        Sudah Dibayar
                    </option>

                    <option value="shipped">
                        Dikirim
                    </option>

                    <option value="completed">
                        Selesai
                    </option>

                </select>

                <select name="bulan" id="bulanFilter" class="filter-select">

                    <option value="">
                        Semua Bulan
                    </option>

                    <?php for ($i = 1; $i <= 12; $i++): ?>

                        <option value="<?= $i ?>">
                            <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                        </option>

                    <?php endfor; ?>

                </select>

                <select name="tahun" id="tahunFilter" class="filter-select">

                    <option value="">
                        Semua Tahun
                    </option>

                    <?php
                    $currentYear = date('Y');

                    for (
                        $year = $currentYear;
                        $year >= 2024;
                        $year--
                    ):
                        ?>

                        <option value="<?= $year ?>">
                            <?= $year ?>
                        </option>

                    <?php endfor; ?>

                </select>

                <select name="sort" id="sortFilter" class="filter-select">

                    <option value="latest">
                        Terbaru
                    </option>

                    <option value="oldest">
                        Terlama
                    </option>

                </select>
            </div>
        </form>

        <div class="table-responsive" id="orderTable">

            <?= $this->render('_table', [
                'orders' => $orders,
                'pages' => $pages,
            ]) ?>

        </div>
    </div>

</div>

<div class="modal fade" id="detailModal">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">
                <h5>Detail Pesanan</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <div id="detail-content"></div>

            </div>

        </div>

    </div>

</div>

<div class="modal fade" id="resiModal">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">
                <h5>Input Nomor Resi</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <input type="text" id="nomor-resi" class="form-control" placeholder="Masukkan nomor resi">

                <input type="hidden" id="order-id-kirim">

            </div>

            <div class="modal-footer">

                <button class="btn btn-success" id="simpan-resi">

                    Simpan
                </button>

            </div>

        </div>

    </div>

</div>

<?php

$url = \yii\helpers\Url::to(['admin/order-detail']);
$urlKirim = \yii\helpers\Url::to(['admin/kirim-pesanan']);

$js = <<<JS

$(document).on("click",".btn-kirim",function(){

    let id = $(this).data("id");

    $("#order-id-kirim").val(id);

    $("#detailModal").modal("hide");

    $("#resiModal").modal("show");
});

$("#simpan-resi").on("click",function(){

    $.post(
        "$urlKirim",
        {
            id: $("#order-id-kirim").val(),
            resi: $("#nomor-resi").val()
        },
        function(res){

            if(res.success){

                alert("Pesanan berhasil dikirim");

                location.reload();

            }else{

                alert("Gagal");
            }
        }
    );
});

$(document).on(
    "click",
    ".btn-detail-order",
    function(){

    let id = $(this).data("id");

    $.getJSON("$url",{id:id},function(res){

        const statusLabel = {
            pending: 'Tertunda',
            paid: 'Sudah Dibayar',
            shipped: 'Dikirim',
            completed: 'Selesai',
            failed: 'Gagal',
            cancelled: 'Dibatalkan'
        };

        const statusText =
            statusLabel[res.order.status] ??
            res.order.status;

        let html = '';

        html += `

<div class="order-summary-card">

    <div class="summary-row">
        <span>Nama Pembeli</span>
        <strong>\${res.order.nama}</strong>
    </div>

    <div class="summary-row">
        <span>No HP</span>
        <strong>\${res.order.no_hp}</strong>
    </div>

    <div class="summary-row">
        <span>Status</span>
        <strong>\${statusText}</strong>
    </div>

    <div class="summary-row">
        <span>Tanggal Pesanan</span>
        <strong>\${res.order.created_at}</strong>
    </div>

    <div class="summary-row">
        <span>Alamat</span>
        <strong>\${res.order.alamat}</strong>
    </div>

</div>

<div class="table-responsive">
<table class="cart-table mt-3">

    <thead>
        <tr>
            <th>Produk</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>
    </thead>

    <tbody>
`;

        res.items.forEach(function(item){

            html += `
                <tr>
                    <td>\${item.produk}</td>
                    <td>\${item.qty}</td>
                    <td>Rp \${Number(item.harga).toLocaleString('id-ID')}</td>
                    <td>Rp \${Number(item.subtotal).toLocaleString('id-ID')}</td>
                </tr>
            `;
        });

        html += `

    </tbody>

</table>
</div>

<div class="order-summary-card mt-3">

    <div class="summary-row">
        <span>Kurir</span>
        <strong>
            \${
                res.order.courier
                ? res.order.courier.toUpperCase()
                : '-'
            }
        </strong>
    </div>
    
    <div class="summary-row">
        <span>Subtotal Produk</span>
        <strong>
            Rp \${Number(res.order.subtotal_produk)
                .toLocaleString('id-ID')}
        </strong>
    </div>

    <div class="summary-row">
        <span>Ongkir</span>
        <strong>
            Rp \${Number(res.order.shipping_cost)
                .toLocaleString('id-ID')}
        </strong>
    </div>

</div>

<div class="order-total-modern">

    <span>Total Bayar</span>

    <strong>
        Rp \${Number(res.order.total)
            .toLocaleString('id-ID')}
    </strong>

</div>

`;

        if(res.order.status === 'paid')
        {
            html += `

                <div class="text-end mt-3">

                    <button
                        class="btn btn-success btn-kirim"
                        data-id="\${res.order.id}">

                        Kirim Pesanan

                    </button>

                </div>

            `;
        }

        $("#detail-content").html(html);

        $("#detailModal").modal("show");
    });
});

function loadOrders() {

    $.ajax({

        url: window.location.href.split('?')[0],

        type: 'GET',

        data: $('#filterForm').serialize(),

        success: function(response) {

            $('#orderTable').html(response);

        }

    });

}

$('#searchOrder').on('keyup', function() {

    clearTimeout(window.orderTimer);

    window.orderTimer = setTimeout(function() {

        loadOrders();

    }, 300);

});

$('#statusFilter').change(loadOrders);
$('#bulanFilter').change(loadOrders);
$('#tahunFilter').change(loadOrders);
$('#sortFilter').change(loadOrders);

$(document).on(
    'click',
    '.pagination a',
    function(e) {

        e.preventDefault();

        $.ajax({

            url: $(this).attr('href'),

            success: function(response) {

                $('#orderTable').html(response);

            }

        });

    }
);

JS;

$this->registerJs($js);
?>