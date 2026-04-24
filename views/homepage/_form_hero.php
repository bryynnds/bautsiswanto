<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var $model app\models\HomepageHero */
if (!is_object($model)) {
    $model = new \app\models\HomepageHero();
}
?>

<div class="form-card">
    <h2 class="section-title mb-4">Ubah Slogan Utama</h2>

    <?php $form = ActiveForm::begin([
        'id' => 'form-hero', 
        'action' => ['homepage/edit-hero'],
        'options' => ['enctype' => 'multipart/form-data'],
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
    ]); ?>

    <!-- Title -->
    <?= $form->field($model, 'title')->textInput() ?>

    <!-- Subtitle -->
    <?= $form->field($model, 'subtitle')->textInput() ?>

    <!-- Background Image -->
    <?= $form->field($model, 'background_image')->fileInput() ?>

    <!-- Preview -->
    <div class="mb-3 text-center">
        <?php if ($model->background_image): ?>
            <img src="/<?= $model->background_image ?>" style="max-width:200px;">
        <?php endif; ?>
    </div>

    <br><br>

    <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>
</div>

<?php
$this->registerJs("
    $('#form-hero').on('afterValidate', function (e, messages, errorAttributes) {
        if (errorAttributes.length > 0) {

            // Hapus alert lama
            $('.custom-alert').remove();

            // Buat alert
            var alertBox = $('<div class=\"alert alert-danger custom-alert\">Mohon lengkapi data hero dengan benar.</div>');

            // Letakkan di bawah judul
            $('.form-card .section-title').after(alertBox);

            // Scroll ke atas
            $('html, body').animate({
                scrollTop: $('.form-card').offset().top - 20
            }, 500);

            // Fokus ke field error pertama
            $('.has-error input, .has-error textarea').first().focus();
        }
    });
");
?>