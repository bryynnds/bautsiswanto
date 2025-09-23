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
    <section class="keranjang loading">
        <h2><?= Html::encode($this->title) ?></h2>

        <?php if (!empty($items)): ?>
            <div class="cart-table-wrapper">
                <table class="cart-table">
                    <thead>
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
                                    <button class="btn-hapus delete-item">Hapus</button>
                                </td>
                                <td>
                                    <img src="<?= Html::encode(Yii::getAlias('@web/' . $produk->image)) ?>"
                                        alt="<?= Html::encode($produk->title) ?>"
                                        class="cart-img">
                                </td>
                                <td><?= Html::encode($produk->title) ?></td>
                                <td>Rp <?= number_format($harga, 0, ',', '.') ?></td>
                                <td>
                                    <div class="qty-control">
                                        <button class="btn-qty minus">-</button>
                                        <input type="text" class="qty" value="<?= (int)$item->jumlah ?>" readonly>
                                        <button class="btn-qty plus">+</button>
                                    </div>
                                </td>
                                <td class="subtotal">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end">Total</th>
                            <th id="grand-total">Rp <?= number_format($grandTotal, 0, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="cart-actions">
                <?= Html::button('Kosongkan Keranjang', [
                    'class' => 'btn-clear',
                    'id' => 'clear-cart'
                ]) ?>

                <?= Html::a('Beli', $checkoutUrl, ['class' => 'btn-checkout', 'id' => 'btn-checkout']) ?>
            </div>
        <?php else: ?>
            <div class="cart-empty text-center">
                <p>Keranjang Anda kosong.</p>
                <?= Html::a('Belanja Sekarang', ['site/index'], ['class' => 'btn-checkout']) ?>
            </div>
        <?php endif; ?>
    </section>
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
                // jika sudah tidak ada item tersisa -> refresh ke halaman keranjang kosong
                if ($('tbody tr').length === 0) {
                    location.reload();
                } else {
                    $('#grand-total').text(formatRupiah(res.grandTotal));
                }
            } else {
                alert('Gagal menghapus item');
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
                // langsung reload biar tampil versi keranjang kosong
                location.reload();
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