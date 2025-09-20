<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'Kontak Kami';
// $this->params['breadcrumbs'][] = $this->title;
?>

<div class="container mt-5">
    <div class="form-card">
        <h2 class="section-title mb-3 text-center"><?= Html::encode($this->title) ?></h2>
        <p class="section-subtitle text-center mb-4">
            Jika ada pertanyaan atau kerja sama, silakan isi form di bawah.
        </p>

        <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
            <div class="alert alert-success text-center">
                Terima kasih sudah menghubungi kami. Kami akan segera membalas pesan Anda.
            </div>
        <?php else: ?>

            <?php $form = ActiveForm::begin([
                'id' => 'contact-form',
                'options' => ['class' => 'form-styled']
            ]); ?>

                <?= $form->field($model, 'name')->textInput([
                    'placeholder' => 'Nama lengkap',
                    'class' => 'form-control'
                ]) ?>

                <?= $form->field($model, 'email')->textInput([
                    'placeholder' => 'Alamat email',
                    'class' => 'form-control'
                ]) ?>

                <?= $form->field($model, 'subject')->textInput([
                    'placeholder' => 'Subjek pesan',
                    'class' => 'form-control'
                ]) ?>

                <?= $form->field($model, 'body')->textarea([
                    'rows' => 6,
                    'placeholder' => 'Tulis pesan Anda di sini...',
                    'class' => 'form-control'
                ]) ?>

                <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                    'template' => '<div class="row">
                                      <div class="col-4">{image}</div>
                                      <div class="col-8">{input}</div>
                                   </div>',
                ]) ?>

                <div class="mt-4 text-center">
                    <?= Html::submitButton('Kirim Pesan', [
                        'class' => 'btn btn-primary px-4'
                    ]) ?>
                </div>

            <?php ActiveForm::end(); ?>

        <?php endif; ?>
    </div>
</div>
