<?php

/** @var yii\web\View $this */
/** @var app\models\HomepageProduk[] $produks */

$this->title = 'Daftar Produk';
?>

<section class="produk mt-2" id="produk">
    <h2>Daftar Produk</h2>
    <div class="produk-grid">
        <?php foreach ($produks as $produk): ?>
            <div class="card">
                <h3><?= $produk->title ?></h3>
                <p>Rp <?= number_format($produk->harga, 0, ',', '.') ?></p>
                <img src="<?= Yii::getAlias('@web') ?>/<?= $produk->image ?>"
                    alt="<?= $produk->title ?>" class="produk-img">
                <p><?= $produk->description ?></p>

                <!-- Stok opsional -->
                <!-- <p><strong>Stok:</strong> <?= $produk->stok ?> pcs</p> -->

                <button class="btn btn-add-cart" data-id="<?= $produk->id ?>">
                    Tambah ke Keranjang
                </button>
            </div>
        <?php endforeach; ?>
    </div>
</section>


<?php
$csrf = Yii::$app->request->csrfToken;
$addUrl = \yii\helpers\Url::to(['cart/add']);
$cartUrl = \yii\helpers\Url::to(['cart/index']);
$js = <<<JS
$(document).on('click', '.btn-add-cart', function() {
    let produkId = $(this).data('id');

    $.ajax({
        url: '/cart/add',
        type: 'POST',
        data: { id: produkId, _csrf: '{$csrf}' },
        success: function(response) {
            let toastEl = document.getElementById('cartToast');
            if (toastEl) {
                let toast = new bootstrap.Toast(toastEl, { delay: 2000 });
                toast.show();
            }
        },
        error: function() {
            alert("Silahkan login terlebih dahulu untuk menambahkan ke keranjang.");
        }
    });
});

$(".btn-add-cart").click(function() {
    var produkId = $(this).data("id");
    $.post("$addUrl", {produk_id: produkId, _csrf: "$csrf"}, function(res) {
        if(res.success) {
            $("#cart-count").text(res.count);
        }
    });
});
JS;

$this->registerJs($js);
?>