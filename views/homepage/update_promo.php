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
            'options' => ['class' => 'form-styled', 'enctype' => 'multipart/form-data']
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
