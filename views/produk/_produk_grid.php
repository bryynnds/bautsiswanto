<?php
use yii\widgets\LinkPager;
?>

<?php foreach ($produks as $produk): ?>

    <div class="card">

        <h3>
            <?= $produk->title ?>
        </h3>

        <p>
            Kiloan:
            Rp
            <?= number_format($produk->harga_kg, 0, ',', '.') ?>
        </p>

        <p>
            Bijian:
            Rp
            <?= number_format($produk->harga_bijian, 0, ',', '.') ?>
        </p>

        <img src="<?= Yii::getAlias('@web') ?>/<?= $produk->image ?>" alt="<?= $produk->title ?>" class="produk-img">

        <p>
            <?= $produk->description ?>
        </p>

        <button class="btn btn-add-cart" data-id="<?= $produk->id ?>">

            Tambah ke Keranjang

        </button>

    </div>

<?php endforeach; ?>

<div class="produk-pagination">

    <?= LinkPager::widget([
        'pagination' => $pages,
    ]) ?>

</div>