<?php
use yii\helpers\Html;

/** @var $model app\models\HomepageProduk */
$this->title = 'Tambah Produk';
?>

<div class="container mt-4">
    <h2><?= Html::encode($this->title) ?></h2>
    <p>Silakan isi detail produk baru di bawah ini:</p>

    <div class="card p-4 shadow-sm">
        <?= $this->render('_form_produk', [
            'model' => $model,
            'produks' => [], // kosong karena ini halaman khusus tambah produk
        ]) ?>
    </div>
</div>
