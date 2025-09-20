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
                <?= Html::submitButton('Login', [
                    'class' => 'btn btn-primary me-2',
                    'name' => 'login-button'
                ]) ?>
                <?= Html::a('Kembali', ['site/index'], [
                    'class' => 'btn btn-secondary'
                ]) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
