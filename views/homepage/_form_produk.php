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
            'id' => 'form-produk',
            'action' => ['homepage/create-produk'],
            'options' => [
                'class' => 'form-styled',
                'enctype' => 'multipart/form-data'
            ],
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
        ]); ?>

        <!-- Nama Produk -->
        <?= $form->field($model, 'title')->textInput([
            'placeholder' => 'Ketik nama produk...',
            'class' => 'form-control'
        ]) ?>

        <?= $form->field($model, 'jenis')->dropDownList(
            [
                'Baut' => 'Baut',
                'Mur' => 'Mur',
                'Ring' => 'Ring',
                'Sekrup' => 'Sekrup'
            ]
            ,
            [
                'prompt' => 'Pilih jenis produk...',
                'class' => 'form-control'
            ]
        ) ?>

        <!-- Deskripsi -->
        <?= $form->field($model, 'description')->textarea([
            'rows' => 4,
            'placeholder' => 'Masukkan deskripsi produk...',
            'class' => 'form-control'
        ]) ?>

        <?= $form->field($model, 'harga_kg')->textInput([
            'type' => 'number',
            'placeholder' => 'Masukkan harga per kg...',
            'min' => 0,
            'class' => 'form-control'
        ]) ?>

        <?= $form->field($model, 'harga_bijian')->textInput([
            'type' => 'number',
            'placeholder' => 'Masukkan harga per bijian...',
            'min' => 0,
            'class' => 'form-control'
        ]) ?>


        <!-- Upload Gambar -->
        <?= $form->field($model, 'image')->fileInput([]) ?>

        <!-- Preview Gambar -->
        <?php if ($model->image): ?>
            <div class="mb-3 text-center">
                <img src="<?= Yii::getAlias('@web') . '/' . $model->image ?>" class="img-fluid rounded shadow-sm"
                    style="max-width:200px;">
            </div>
        <?php endif; ?>

        <!-- Tombol -->
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

<?php
$this->registerJs("
    $('#form-produk').on('afterValidate', function (e, messages, errorAttributes) {
        if (errorAttributes.length > 0) {

            // Hapus alert lama
            $('.custom-alert').remove();

            // Buat alert
            var alertBox = $('<div class=\"alert alert-danger custom-alert\">Mohon lengkapi data produk dengan benar.</div>');

            // Letakkan setelah judul
            $('.section-title').after(alertBox);

            // Scroll ke atas
            $('html, body').animate({
                scrollTop: $('.form-card').offset().top - 20
            }, 500);
        }
    });
");
?>