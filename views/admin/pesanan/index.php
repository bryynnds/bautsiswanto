<?php

use yii\helpers\Html;

$this->title = 'Daftar Pesanan';
?>

<div class="container-fluid">

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="mb-0">Daftar Pesanan</h3>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead class="table-dark">
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
                                <?= $order->status ?>
                            </td>

                            <td>
                                <?= Yii::$app->formatter->asDatetime(
                                    $order->created_at,
                                    'php:d-m-Y H:i'
                                ) ?>
                            </td>
                            <td>
                                <button class="btn btn-info btn-detail" data-id="<?= $order->id ?>">

                                    Detail
                                </button>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

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

$(".btn-detail").on("click", function(){

    let id = $(this).data("id");

    $.getJSON("$url",{id:id},function(res){

        let html = '';

        html += `
            <p><b>Nama :</b> \${res.order.nama}</p>
            <p><b>No HP :</b> \${res.order.no_hp}</p>
            <p><b>Alamat :</b> \${res.order.alamat}</p>
            <p><b>Status :</b> \${res.order.status}</p>

            <div id="kirim-area"></div>

            <hr>

            <table class="table table-bordered">

                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
        `;

        res.items.forEach(function(item){

            html += `
                <tr>
                    <td>\${item.produk}</td>
                    <td>\${item.qty}</td>
                    <td>Rp \${Number(item.harga).toLocaleString()}</td>
                    <td>Rp \${Number(item.subtotal).toLocaleString()}</td>
                </tr>
            `;
        });

        html += `
            </table>

            <hr>

            <p><b>Kurir :</b> \${res.order.courier}</p>

            <p><b>Ongkir :</b>
            Rp \${Number(res.order.shipping_cost).toLocaleString()}
            </p>

            <h4>
                Total :
                Rp \${Number(res.order.total).toLocaleString()}
            </h4>
        `;

        if(res.order.status === 'paid')
{
    html += `
        <hr>

        <button
            class="btn btn-success btn-kirim"
            data-id="\${res.order.id}">

            Kirim Pesanan
        </button>
    `;
}

        $("#detail-content").html(html);

        $("#detailModal").modal("show");
    });
});

JS;

$this->registerJs($js);
?>