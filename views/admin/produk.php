<?php

/** @var yii\web\View $this */
/** @var app\models\HomepageProduk[] $produk */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Daftar Produk';
?>

<section class="produk mt-2" id="produk">

    <h2 style="margin-bottom: 20px;">Daftar Produk</h2>

    <div class="mb-4">

        <a href="<?= Url::to(['homepage/create-produk']) ?>" class="btn btn-primary me-2">
            Tambah Produk
        </a>

        <a href="<?= Url::to(['homepage/create-kategori']) ?>" class="btn btn-success">
            Tambah Kategori
        </a>

    </div>

    <div class="produk-grid">

        <?php foreach ($produk as $item): ?>

            <div class="card">

                <!-- Nama Produk -->
                <h3><?= Html::encode($item->title) ?></h3>

                <!-- Jenis Produk -->
                <p>
                    <strong>Jenis:</strong>
                    <?= Html::encode(
                        $item->kategori->jenis->nama_jenis ?? '-'
                    ) ?>
                </p>

                <!-- Kategori Produk -->
                <p>
                    <strong>Kategori:</strong>
                    <?= Html::encode(
                        $item->kategori->nama_kategori ?? '-'
                    ) ?>
                </p>

                <!-- Harga -->
                <p>
                    Harga per Kg :
                    Rp <?= number_format($item->harga_kg, 0, ',', '.') ?>
                </p>

                <p>
                    Harga per Biji :
                    Rp <?= number_format($item->harga_bijian, 0, ',', '.') ?>
                </p>

                <!-- Gambar -->
                <img src="<?= Yii::getAlias('@web') ?>/<?= Html::encode($item->image) ?>"
                    alt="<?= Html::encode($item->title) ?>" class="produk-img">

                <!-- Deskripsi -->
                <p><?= Html::encode($item->description) ?></p>

                <!-- Tombol -->
                <div class="d-flex justify-content-between mt-2">

                    <?= Html::a(
                        'Ubah',
                        ['homepage/update-produk', 'id' => $item->id],
                        [
                            'class' => 'btn btn-warning btn-sm w-50 me-1',
                        ]
                    ) ?>

                    <?= Html::a(
                        'Hapus',
                        ['homepage/delete-produk', 'id' => $item->id],
                        [
                            'class' => 'btn btn-danger btn-sm w-50',
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