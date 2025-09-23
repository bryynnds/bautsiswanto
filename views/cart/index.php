<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Keranjang[] $items */

$this->title = 'Keranjang Saya';

$updateUrl  = Url::to(['cart/update-qty']);
$deleteUrl  = Url::to(['cart/delete']);
$clearUrl   = Url::to(['cart/clear']);
$checkoutUrl = Url::to(['cart/checkout']);
$csrf       = Yii::$app->request->csrfToken;
?>

<div class="container mt-4">
    <h2><?= Html::encode($this->title) ?></h2>

    <table class="table table-bordered align-middle text-center">
        <thead class="table-light">
            <tr>
                <th>Aksi</th>
                <th>Gambar</th>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                
            </tr>
        </thead>
        <tbody>
            <?php $grandTotal = 0; ?>
            <?php foreach ($items as $item): ?>
                <?php
                $produk = $item->produk;
                if (!$produk) continue;
                $harga = (int)$produk->harga;
                $subtotal = $harga * (int)$item->jumlah;
                $grandTotal += $subtotal;
                ?>
                <tr data-id="<?= (int)$item->id ?>" data-harga="<?= $harga ?>">
                    <td>
                        <button class="btn btn-danger btn-sm delete-item">Hapus</button>
                    </td>
                    <td>
                        <img src="<?= Html::encode(Yii::getAlias('@web/' . $produk->image)) ?>"
                            alt="<?= Html::encode($produk->title) ?>"
                            class="img-thumbnail" style="width:100px;">
                    </td>
                    <td class="text-start"><?= Html::encode($produk->title) ?></td>
                    <td>Rp <?= number_format($harga, 0, ',', '.') ?></td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center">
                            <button class="btn btn-outline-secondary btn-sm minus">-</button>
                            <input type="text" class="form-control mx-1 text-center qty"
                                value="<?= (int)$item->jumlah ?>" style="width: 60px;" readonly>
                            <button class="btn btn-outline-secondary btn-sm plus">+</button>
                        </div>
                    </td>
                    <td class="subtotal">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if (count($items) === 0): ?>
                <tr>
                    <td colspan="6">Keranjang kosong</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-end">Total</th>
                <th id="grand-total">Rp <?= number_format($grandTotal, 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>

    <!-- tombol berada di luar table, kiri = kosongkan, kanan = beli -->
    <div class="d-flex justify-content-between mt-3">
        <div>
            <?= Html::button('Kosongkan Keranjang', [
                'class' => 'btn btn-warning',
                'id' => 'clear-cart'
            ]) ?>
        </div>

        <div>
            <?= Html::a('Beli', $checkoutUrl, ['class' => 'btn btn-success', 'id' => 'btn-checkout']) ?>
        </div>
    </div>

</div>

<?php
$script = <<<JS
function formatRupiah(num) {
    num = Number(num) || 0;
    return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// plus / minus -> update qty via AJAX (update-qty)
$(document).on('click', '.plus, .minus', function(e){
    e.preventDefault();
    var row = $(this).closest('tr');
    var qtyInput = row.find('.qty');
    var qty = parseInt(qtyInput.val(), 10) || 1;

    if ($(this).hasClass('plus')) {
        qty++;
    } else {
        if (qty > 1) qty--;
        else return;
    }

    // Optimistik UI
    qtyInput.val(qty);

    // kirim ke server
    $.ajax({
        url: '{$updateUrl}',
        method: 'POST',
        dataType: 'json',
        data: {
            id: row.data('id'),
            qty: qty,
            _csrf: '{$csrf}'
        },
        success: function(res) {
            if (res && res.success) {
                row.find('.subtotal').text(formatRupiah(res.subtotal));
                $('#grand-total').text(formatRupiah(res.grandTotal));
            } else {
                alert('Gagal update jumlah: ' + (res && res.error ? res.error : 'Unknown'));
                // optional: revert or reload
                location.reload();
            }
        },
        error: function(xhr, status, err) {
            console.error('AJAX error', status, err, xhr.responseText);
            alert('Terjadi error pada server. Cek console (F12).');
            location.reload();
        }
    });
});

// delete per item (AJAX)
$(document).on('click', '.delete-item', function(e){
    e.preventDefault();
    if (!confirm('Yakin ingin menghapus produk ini dari keranjang?')) return;

    var row = $(this).closest('tr');
    var id = row.data('id');

    $.ajax({
        url: '{$deleteUrl}',
        method: 'POST',
        dataType: 'json',
        data: {
            id: id,
            _csrf: '{$csrf}'
        },
        success: function(res) {
            if (res && res.success) {
                row.remove();
                $('#grand-total').text(formatRupiah(res.grandTotal));

                // jika kosong, tampilkan pesan kosong
                if ($('tbody tr').length === 0) {
                    $('tbody').append('<tr><td colspan="6">Keranjang kosong</td></tr>');
                }
            } else {
                alert('Gagal menghapus item: ' + (res && res.error ? res.error : 'Unknown'));
            }
        },
        error: function(xhr, status, err) {
            console.error('AJAX error', status, err, xhr.responseText);
            alert('Terjadi error server saat menghapus item.');
        }
    });
});

// kosongkan keranjang (AJAX)
$(document).on('click', '#clear-cart', function(e){
    e.preventDefault();
    if (!confirm('Yakin ingin mengosongkan seluruh keranjang?')) return;

    $.ajax({
        url: '{$clearUrl}',
        method: 'POST',
        dataType: 'json',
        data: {
            _csrf: '{$csrf}'
        },
        success: function(res) {
            if (res && res.success) {
                // kosongkan tbody dan update total
                $('tbody').empty();
                $('tbody').append('<tr><td colspan="6">Keranjang kosong</td></tr>');
                $('#grand-total').text(formatRupiah(0));
            } else {
                alert('Gagal mengosongkan keranjang');
            }
        },
        error: function(xhr, status, err) {
            console.error('AJAX error', status, err, xhr.responseText);
            alert('Terjadi error server saat mengosongkan keranjang.');
        }
    });
});
JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>