<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var app\models\HomepageKeunggulan $model */
$this->title = 'Admin - Ubah Keunggulan';
?>

<div class="container mt-5">
    <div class="form-card">
        <h2 class="section-title mb-4">Ubah Keunggulan</h2>

        <?php $form = ActiveForm::begin([
            'id' => 'form-update-keunggulan', // ✅ WAJIB
            'options' => ['class' => 'form-styled'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
        ]); ?>

        <?= $form->field($model, 'title')->textInput([
            'placeholder' => 'Ketik disini',
            'class' => 'form-control'
        ]) ?>

        <?= $form->field($model, 'subtitle')->textInput([
            'placeholder' => 'Ketik disini',
            'class' => 'form-control'
        ]) ?>

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
    $('#form-update-keunggulan').on('afterValidate', function (e, messages, errorAttributes) {
        if (errorAttributes.length > 0) {

            $('.custom-alert').remove();

            var alertBox = $('<div class=\"alert alert-danger custom-alert\">Mohon lengkapi data keunggulan dengan benar.</div>');

            $('.form-card .section-title').after(alertBox);

            $('html, body').animate({
                scrollTop: $('.form-card').offset().top - 20
            }, 500);

            $('.has-error input').first().focus();
        }
    });
");
?>