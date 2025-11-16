<?php
use yii\helpers\Html;

/** @var $model app\models\HomepageProduk */
$this->title = 'Tambah Produk';
?>

<div class="container mt-4">

    <div>
        <?= $this->render('_form_produk', [
            'model' => $model,
            'produks' => [], // kosong karena ini halaman khusus tambah produk
        ]) ?>
    </div>
</div>
