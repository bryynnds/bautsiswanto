<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\SignupForm $model */

$this->title = 'Daftar Akun';
?>

<div class="container mt-5">
    <div class="form-card">
        <h2 class="section-title mb-4 text-center"><?= Html::encode($this->title) ?></h2>

        <?php $form = ActiveForm::begin([
            'id' => 'signup-form',
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

        <?= $form->field($model, 'confirmPassword')->passwordInput([
            'placeholder' => 'Ulangi Password...'
        ]) ?>

        <div class="mt-4 d-flex justify-content-between">
            <?= Html::submitButton('Daftar', [
                'class' => 'btn btn-primary me-2',
                'name' => 'login-button'
            ]) ?>
        </div>

        <div class="mt-3 text-center">
            <?= Html::a('Sudah punya akun? Masuk disini', ['site/login']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>