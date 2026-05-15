<?php

/** @var yii\web\View $this */
/** @var app\models\HomepageProduk[] $produk */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Daftar Produk';
?>

<section class="produk mt-2" id="produk">
    <h2 style="margin-bottom: 20px;">Daftar Produk</h2>
    <div>
        <a href="<?= \yii\helpers\Url::to(['homepage/create-produk']) ?>" class="btn btn-primary mb-4 me-2">
            Tambah Produk
        </a>
    </div>

    <div class="produk-grid">
        <?php foreach ($produk as $item): ?>
            <div class="card">
                <h3><?= Html::encode($item->title) ?></h3>
                <p>Harga per Kg : Rp <?= number_format($item->harga_kg, 0, ',', '.') ?></p>
                <p>Harga per Biji : Rp <?= number_format($item->harga_bijian, 0, ',', '.') ?></p>

                <img src="<?= Yii::getAlias('@web') ?>/<?= Html::encode($item->image) ?>"
                    alt="<?= Html::encode($item->title) ?>"
                    class="produk-img">

                <p><?= Html::encode($item->description) ?></p>

                <div class="d-flex justify-content-between mt-2">
                    <?= Html::a('Ubah', ['homepage/update-produk', 'id' => $item->id], [
                        'class' => 'btn btn-warning btn-sm w-50 me-1',
                    ]) ?>

                    <?= Html::a('Hapus', ['homepage/delete-produk', 'id' => $item->id], [
                        'class' => 'btn btn-danger btn-sm w-50',
                        'data' => [
                            'confirm' => 'Yakin ingin menghapus produk ini?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>