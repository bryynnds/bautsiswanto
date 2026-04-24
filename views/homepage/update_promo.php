<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var app\models\HomepagePromo $model */
$this->title = 'Admin - Ubah Promo';
?>

<div class="container mt-5">
    <div class="form-card">
        <h2 class="section-title mb-4">Ubah Promo</h2>

        <?php $form = ActiveForm::begin([
            'id' => 'form-update-promo', // ✅ WAJIB
            'options' => [
                'class' => 'form-styled',
                'enctype' => 'multipart/form-data'
            ],
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
        ]); ?>

        <?= $form->field($model, 'title')->textInput(['placeholder' => 'Judul promo...']) ?>
        <?= $form->field($model, 'start_date')->input('date') ?>
        <?= $form->field($model, 'end_date')->input('date') ?>
        <?= $form->field($model, 'imageFile')->fileInput() ?>

        <?php if ($model->image): ?>
            <div class="mb-3 text-center">
                <img src="<?= Yii::getAlias('@web') . '/' . $model->image ?>"
                     class="img-fluid rounded shadow-sm"
                     style="max-width:200px;">
            </div>
        <?php endif; ?>

        <div class="mt-4">
            <?= Html::submitButton('Simpan Perubahan', ['class' => 'btn btn-primary me-2']) ?>
            <?= Html::a('Kembali', ['homepage/edit'], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$this->registerJs("
    $('#form-update-promo').on('afterValidate', function (e, messages, errorAttributes) {
        if (errorAttributes.length > 0) {

            $('.custom-alert').remove();

            var alertBox = $('<div class=\"alert alert-danger custom-alert\">Mohon lengkapi data promo dengan benar.</div>');

            $('.form-card .section-title').after(alertBox);

            $('html, body').animate({
                scrollTop: $('.form-card').offset().top - 20
            }, 500);

            $('.has-error input, .has-error textarea').first().focus();
        }
    });
");
?>