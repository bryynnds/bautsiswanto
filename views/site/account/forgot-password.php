<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\ForgotPasswordForm $model */

$this->title = 'Lupa Password';
?>

<div class="container mt-5">
    <div class="form-card">

        <h2 class="section-title mb-4">
            <?= Html::encode($this->title) ?>
        </h2>

        <p class="text-center text-muted mb-4">
            Masukkan email yang terdaftar untuk menerima link reset password.
        </p>

        <?php $form = ActiveForm::begin([
            'id' => 'forgot-password-form',
            'options' => ['class' => 'form-styled'],
            'fieldConfig' => [
                'template' => "{input}\n{error}",
                'inputOptions' => ['class' => 'form-control'],
                'errorOptions' => ['class' => 'invalid-feedback'],
            ],
        ]); ?>

        <?= $form->field($model, 'email')->textInput([
            'placeholder' => 'Email...'
        ]) ?>

        <div class="mt-4 d-flex justify-content-between">
            <?= Html::submitButton(
                'Kirim Link Reset',
                ['class' => 'btn btn-primary']
            ) ?>
        </div>

        <div class="mt-3 text-center">
            <?= Html::a(
                'Kembali ke Login',
                ['site/login']
            ) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>