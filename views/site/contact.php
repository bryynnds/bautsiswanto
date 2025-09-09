<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

// $this->title = 'Kontak Kami';
// $this->params['breadcrumbs'][] = $this->title;
?>

<section id="kontak" class="contact-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="section-title"><?= Html::encode($this->title) ?></h1>
            <p class="section-subtitle">Jika ada pertanyaan atau kerja sama, silakan isi form di bawah.</p>
        </div>

        <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
            <div class="alert alert-success text-center">
                Terima kasih sudah menghubungi kami. Kami akan segera membalas pesan Anda.
            </div>
        <?php else: ?>

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                        <?= $form->field($model, 'name')->textInput(['placeholder' => 'Nama lengkap']) ?>

                        <?= $form->field($model, 'email')->textInput(['placeholder' => 'Alamat email']) ?>

                        <?= $form->field($model, 'subject')->textInput(['placeholder' => 'Subjek pesan']) ?>

                        <?= $form->field($model, 'body')->textarea(['rows' => 6, 'placeholder' => 'Tulis pesan Anda di sini...']) ?>

                        <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                            'template' => '<div class="row"><div class="col-4">{image}</div><div class="col-8">{input}</div></div>',
                        ]) ?>

                        <div class="form-group text-center mt-4">
                            <?= Html::submitButton('Kirim Pesan', ['class' => 'btn btn-primary btn-lg px-4']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>

        <?php endif; ?>
    </div>
</section>
