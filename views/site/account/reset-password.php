<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\ResetPasswordForm $model */

$this->title = 'Reset Password';
?>

<div class="container mt-5">
    <div class="form-card">

        <h2 class="section-title mb-4">
            <?= Html::encode($this->title) ?>
        </h2>

        <?php $form = ActiveForm::begin([
            'id' => 'reset-password-form',
            'options' => ['class' => 'form-styled'],
            'fieldConfig' => [
                'template' => "{input}\n{error}",
                'inputOptions' => ['class' => 'form-control'],
                'errorOptions' => ['class' => 'invalid-feedback'],
            ],
        ]); ?>

        <?= $form->field($model, 'password')->passwordInput([
            'placeholder' => 'Password Baru...'
        ]) ?>

        <?= $form->field($model, 'confirmPassword')->passwordInput([
            'placeholder' => 'Ulangi Password Baru...'
        ]) ?>

        <div class="mt-4 d-flex justify-content-between">
            <?= Html::submitButton(
                'Simpan Password Baru',
                ['class' => 'btn btn-primary']
            ) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>