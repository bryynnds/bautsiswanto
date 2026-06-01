<?php

/** @var yii\web\View $this */
/** @var app\models\HomepageProduk[] $produk */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Daftar Produk';
?>

<section class="produk mt-2" id="produk">

    <h2 style="margin-bottom: 20px;">Daftar Produk</h2>

    <div class="produk-action mb-4">

        <a href="<?= Url::to(['homepage/create-produk']) ?>" class="btn btn-primary">
            + Tambah Produk
        </a>

        <a href="<?= Url::to(['homepage/create-kategori']) ?>" class="btn btn-primary">
            + Tambah Kategori
        </a>

    </div>

    <div class="produk-grid">

        <?php foreach ($produk as $item): ?>

            <div class="card">

                <h3><?= Html::encode($item->title) ?></h3>

                <img src="<?= Yii::getAlias('@web') ?>/<?= Html::encode($item->image) ?>"
                    alt="<?= Html::encode($item->title) ?>" class="produk-img">

                

                <div class="harga-box">

                    <div>
                        <small>Harga / Kg</small>
                        <strong>
                            Rp <?= number_format($item->harga_kg, 0, ',', '.') ?>
                        </strong>
                    </div>

                    <div>
                        <small>Harga / Biji</small>
                        <strong>
                            Rp <?= number_format($item->harga_bijian, 0, ',', '.') ?>
                        </strong>
                    </div>

                </div>


                <!-- Tombol -->
                <div class="btn-wrapper">

                    <?= Html::a(
                        'Ubah',
                        ['homepage/update-produk', 'id' => $item->id],
                        [
                            'class' => 'btn-edit',
                        ]
                    ) ?>

                    <?= Html::a(
                        'Hapus',
                        ['homepage/delete-produk', 'id' => $item->id],
                        [
                            'class' => 'btn-hapus',
                            'data' => [
                                'confirm' => 'Yakin ingin menghapus produk ini?',
                                'method' => 'post',
                            ],
                        ]
                    ) ?>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</section>