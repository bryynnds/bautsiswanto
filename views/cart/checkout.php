<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Keranjang[] $items */

$searchDestinationUrl = Url::to(['cart/get-provinces']);
$searchUrl = Url::to(['cart/get-provinces']);
$ongkirUrl = Url::to(['cart/calculate-ongkir']);

$this->title = 'Checkout';
?>

<div class="container mt-5">
    <section class="checkout-section loading">
        <h2 class="section-title">
            Checkout Pesanan
        </h2>

        <p class="checkout-subtitle">
            Lengkapi data pengiriman dan lakukan pembayaran.
        </p>

        <?php if (!empty($items)): ?>

            <?php
            // Hitung grand total dulu supaya bisa digunakan di form
            $grandTotal = 0;
            foreach ($items as $item) {
                $harga = $item->satuan == 'kg'
                    ? (int) $item->produk->harga_kg
                    : (int) $item->produk->harga_bijian;
                $subtotal = $harga * $item->jumlah;
                $grandTotal += $subtotal;
            }

            $grandTotalJs = $grandTotal;
            $totalBerat = 0;

            foreach ($items as $item) {

                if ($item->satuan == 'kg') {

                    $beratItem = $item->jumlah * 1000;

                } else {

                    $beratItem = $item->jumlah * $item->produk->berat;
                }

                $totalBerat += $beratItem;
            }
            ?>
            <input type="hidden" id="total-berat" value="<?= $totalBerat ?>">

            <div class="row mt-4">
                <!-- Form Data -->
                <div class="col-md-6">
                    <div class="checkout-card">
                        <?php $form = ActiveForm::begin([
                            'action' => ['/checkout/process'], // sesuaikan route jika beda
                            'method' => 'post',
                        ]); ?>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3" required></textarea>
                        </div>

                        <input type="hidden" name="shipping_cost" id="shipping-cost-input" value="0">

                        <input type="hidden" name="destination_id" id="destination-id">
                        <input type="hidden" name="province" id="province-name">
                        <input type="hidden" name="city" id="city-name">
                        <input type="hidden" name="postal_code" id="postal-code">



                        <div class="mb-3">
                            <label class="form-label">
                                Cari Tujuan Pengiriman
                            </label>

                            <input type="text" id="search-destination" class="form-control"
                                placeholder="Contoh: Solo, Surabaya, Jakarta">

                            <div id="destination-results" class="list-group mt-2">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="no_hp" class="form-label">No HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran</label>
                            <select name="metode_pembayaran" class="form-select" required>
                                <option value="COD">COD</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                            </select>
                        </div>

                        <button type="submit" class="btn-checkout btn btn-primary">Bayar</button>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>

                <!-- Ringkasan Order -->
                <div class="col-md-6">
                    <div class="checkout-card">
                        <h4>Ringkasan Pesanan</h4>
                        <div class="cart-table-wrapper">
                            <table class="cart-table table">
                                <thead>
                                    <tr class="checkout-item-row">
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Satuan</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): ?>
                                        <?php
                                        $harga = $item->satuan == 'kg'
                                            ? (int) $item->produk->harga_kg
                                            : (int) $item->produk->harga_bijian;
                                        $subtotal = $harga * $item->jumlah;
                                        ?>
                                        <tr class="checkout-item-row">
                                            <td data-label="Produk"><?= Html::encode($item->produk->title) ?></td>
                                            <td data-label="Harga">Rp <?= number_format($harga, 0, ',', '.') ?></td>
                                            <td data-label="Satuan"><?= ucfirst($item->satuan) ?></td>

                                            <td data-label="Jumlah"><?= $item->jumlah ?></td>
                                            <td data-label="Subtotal">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">
                                            Ongkir
                                        </th>

                                        <th id="shipping-cost">
                                            -
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-end">
                                            Total Bayar
                                        </th>

                                        <th id="final-total">
                                            Rp <?= number_format($grandTotal, 0, ',', '.') ?>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="cart-empty text-center mt-4">
                <p>Keranjang Anda kosong.</p>
                <?= Html::a('Belanja Sekarang', ['site/index'], ['class' => 'btn-checkout btn btn-primary']) ?>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php

$script = <<<JS

function formatRupiah(num) {

    return 'Rp ' + Number(num)
        .toLocaleString('id-ID');
}

let searchTimeout;

$('#search-destination').on('input', function(){

    clearTimeout(searchTimeout);

    let keyword = $(this).val();

    if(keyword.length < 5){

        $('#destination-results').html('');
        return;
    }

    searchTimeout = setTimeout(function(){

        $.ajax({

            url: '$searchUrl',

            method: 'GET',

            data: {
                search: keyword
            },

            success: function(res){

                let html = '';

                if(res.data && res.data.data){

                    res.data.data.forEach(function(item){

                        html += `
                            <button type="button"
                                    class="list-group-item list-group-item-action destination-item"
                                    data-id="\${item.id}"
                                    data-province="\${item.province_name}"
                                    data-city="\${item.city_name}"
                                    data-postal="\${item.zip_code}">
                                    
                                \${item.label}
                            </button>
                        `;
                    });
                }

                $('#destination-results').html(html);
            }
        });

    }, 800); // tunggu 0.8 detik setelah user berhenti mengetik
});

$(document).on('click', '.destination-item', function(){

    $('#destination-id').val($(this).data('id'));

    $('#province-name').val($(this).data('province'));

    $('#city-name').val($(this).data('city'));

    $('#postal-code').val($(this).data('postal'));

    $('#search-destination').val(
    $(this).text().trim()
);

$('#search-destination').css({
    'text-align': 'left',
    'direction': 'ltr'
});

    $('#destination-results').html('');

    let destinationId = $(this).data('id');

    let weight = $('#total-berat').val();

    $.ajax({

        url: '$ongkirUrl',

        method: 'POST',

        data: {
            destination: destinationId,
            weight: weight
        },

        success: function(res){

            console.log(res);

            if(res.data){

                let ongkir = res.data[0].cost;

                $('#shipping-cost')
                    .text(formatRupiah(ongkir));

                $('#shipping-cost-input')
                    .val(ongkir);

                let grandTotal = $grandTotalJs;

                let finalTotal = grandTotal + ongkir;

                $('#final-total')
                    .text(formatRupiah(finalTotal));
            }
        }
    });
});

JS;

$this->registerJs($script);
?>