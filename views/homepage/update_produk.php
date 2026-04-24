<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var app\models\HomepageProduk $model */
$this->title = 'Admin - Ubah Produk';
?>

<div class="container mt-5">
    <div class="form-card">
        <h2 class="section-title mb-4">Ubah Produk</h2>

        <?php $form = ActiveForm::begin([
            'id' => 'form-produk-update',
            'options' => [
                'class' => 'form-styled',
                'enctype' => 'multipart/form-data'
            ],
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
        ]); ?>


        <?= $form->field($model, 'title')->textInput([
            'placeholder' => 'Ketik disini',
            'class' => 'form-control'
        ]) ?>

        <?= $form->field($model, 'description')->textarea([
            'rows' => 4,
            'placeholder' => 'Ketik disini',
            'class' => 'form-control'
        ]) ?>

        <?= $form->field($model, 'harga')->textInput([
            'type' => 'number',
            'placeholder' => 'Masukkan harga produk',
            'class' => 'form-control'
        ]) ?>

        <?= $form->field($model, 'stok')->textInput([
            'type' => 'number',
            'placeholder' => 'Masukkan jumlah stok',
            'class' => 'form-control'
        ]) ?>

        <?= $form->field($model, 'image')->fileInput([]) ?>

        <?php if ($model->image): ?>
            <div class="mb-3 text-center">
                <img src="<?= Yii::getAlias('@web') . '/' . $model->image ?>"
                    class="img-fluid rounded shadow-sm"
                    style="max-width:200px;">
            </div>
        <?php endif; ?>

        <div class="mt-4">
            <?= Html::submitButton('Simpan Perubahan', [
                'class' => 'btn btn-primary me-2'
            ]) ?>
            <?= Html::a('Kembali', ['homepage/edit'], [
                'class' => 'btn btn-secondary'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$this->registerJs("
    $('#form-produk-update').on('afterValidate', function (e, messages, errorAttributes) {
        if (errorAttributes.length > 0) {

            // Hapus alert lama
            $('.custom-alert').remove();

            // Buat alert
            var alertBox = $('<div class=\"alert alert-danger custom-alert\">Mohon lengkapi data produk dengan benar.</div>');

            // Letakkan di bawah judul
            $('.form-card .section-title').after(alertBox);

            // Scroll ke atas
            $('html, body').animate({
                scrollTop: $('.form-card').offset().top - 20
            }, 500);

            // Fokus ke field error pertama (bonus UX)
            $('.has-error input, .has-error textarea').first().focus();
        }
    });
");
?>