<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Masuk Akun';
?>

<div class="container mt-5">
    <div class="form-card">
        <h2 class="section-title mb-4 text-center"><?= Html::encode($this->title) ?></h2>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'enableClientValidation' => true, // ✅ TAMBAHAN
            'enableAjaxValidation' => false,
            'options' => ['class' => 'form-styled'],
            'fieldConfig' => [
                'template' => "{input}\n{error}",
                'inputOptions' => ['class' => 'form-control'],
                'errorOptions' => ['class' => 'invalid-feedback'],
            ],
        ]); ?>

        <?= $form->field($model, 'username')->textInput([
            'autofocus' => true,
            'placeholder' => 'Username...'
        ]) ?>

        <?= $form->field($model, 'password')->passwordInput([
            'placeholder' => 'Password...'
        ]) ?>

        <div class="form-check mb-3">
            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "{input} {label}\n{error}",
                'labelOptions' => ['class' => 'form-check-label'],
                'class' => 'form-check-input'
            ]) ?>
        </div>

        <div class="mt-4 d-flex justify-content-between">
            <?= Html::submitButton('Masuk', [
                'class' => 'btn btn-primary me-2',
                'name' => 'login-button'
            ]) ?>
        </div>

        <div class="mt-3 text-center">
            <?= Html::a('Belum punya akun? Daftar di sini', ['site/signup']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$this->registerJs("
    $('#login-form').on('afterValidate', function (e, messages, errorAttributes) {
        if (errorAttributes.length > 0) {

            // Hapus alert lama
            $('.custom-alert').remove();

            // Buat alert
            var alertBox = $('<div class=\"alert alert-danger custom-alert text-center\">Mohon lengkapi data login dengan benar.</div>');

            // Letakkan di bawah judul
            $('.form-card .section-title').after(alertBox);

            // Scroll ke atas
            $('html, body').animate({
                scrollTop: $('.form-card').offset().top - 20
            }, 500);

            // Fokus ke field pertama yang error
            $('.has-error input').first().focus();
        }
    });
");
?>