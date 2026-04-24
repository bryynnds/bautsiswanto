<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var app\models\HomepageTestimoni $model */
$this->title = 'Admin - Ubah Testimoni';
?>

<div class="container mt-5">
    <div class="form-card">
        <h2 class="section-title mb-4">Ubah Testimoni</h2>

        <?php $form = ActiveForm::begin([
            'id' => 'form-update-testimoni', // ✅ WAJIB
            'options' => ['class' => 'form-styled'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
        ]); ?>

        <?= $form->field($model, 'content')->textarea([
            'rows' => 4,
            'placeholder' => 'Ketik disini',
            'class' => 'form-control'
        ]) ?>

        <?= $form->field($model, 'author')->textInput([
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
    $('#form-update-testimoni').on('afterValidate', function (e, messages, errorAttributes) {
        if (errorAttributes.length > 0) {

            $('.custom-alert').remove();

            var alertBox = $('<div class=\"alert alert-danger custom-alert\">Mohon lengkapi data testimoni dengan benar.</div>');

            $('.form-card .section-title').after(alertBox);

            $('html, body').animate({
                scrollTop: $('.form-card').offset().top - 20
            }, 500);

            $('.has-error textarea, .has-error input').first().focus();
        }
    });
");
?>