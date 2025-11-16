<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var $model app\models\HomepageProduk */

if (!is_object($model)) {
    $model = new \app\models\HomepageProduk();
}

$this->title = 'Admin - Tambah Produk';
?>

<div class="container mt-5">
    <div class="form-card">
        <h2 class="section-title mb-4">Tambah Produk</h2>

        <?php $form = ActiveForm::begin([
            'action' => ['homepage/create-produk'],
            'options' => ['class' => 'form-styled', 'enctype' => 'multipart/form-data']
        ]); ?>

        <!-- Nama Produk -->
        <?= $form->field($model, 'title')->textInput([
            'placeholder' => 'Ketik nama produk...',
            'class' => 'form-control'
        ]) ?>

        <!-- Merek -->
        <?= $form->field($model, 'brand_name')->textInput([
            'placeholder' => 'Masukkan merek produk...',
            'class' => 'form-control'
        ]) ?>

        <!-- Deskripsi -->
        <?= $form->field($model, 'description')->textarea([
            'rows' => 4,
            'placeholder' => 'Masukkan deskripsi produk...',
            'class' => 'form-control'
        ]) ?>

        <!-- Harga -->
        <?= $form->field($model, 'harga')->textInput([
            'type' => 'number',
            'placeholder' => 'Masukkan harga produk...',
            'min' => 0,
            'class' => 'form-control'
        ]) ?>

        <!-- Jumlah Stok -->
        <?= $form->field($model, 'stok')->textInput([
            'type' => 'number',
            'placeholder' => 'Masukkan jumlah stok...',
            'min' => 0,
            'class' => 'form-control'
        ]) ?>

        <!-- Link Produk -->
        <?= $form->field($model, 'link')->textInput([
            'placeholder' => 'Masukkan link produk...',
            'class' => 'form-control'
        ]) ?>

        <!-- Upload Gambar -->
        <?= $form->field($model, 'image')->fileInput([]) ?>

        <!-- Preview Gambar (jika ada) -->
        <?php if ($model->image): ?>
            <div class="mb-3 text-center">
                <img src="<?= Yii::getAlias('@web') . '/' . $model->image ?>"
                    class="img-fluid rounded shadow-sm"
                    style="max-width:200px;">
            </div>
        <?php endif; ?>

        <!-- Tombol Aksi -->
        <div class="mt-4">
            <?= Html::submitButton('Tambah Produk', [
                'class' => 'btn btn-success me-2'
            ]) ?>
            <?= Html::a('Kembali', ['admin/produk'], [
                'class' => 'btn btn-secondary'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
