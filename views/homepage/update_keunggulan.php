<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var app\models\HomepageKeunggulan $model */
?>

<div class="container mt-5">
    <div class="form-card">
        <h2 class="section-title mb-4">Ubah Keunggulan</h2>

        <?php $form = ActiveForm::begin([
            'options' => ['class' => 'form-styled']
        ]); ?>

            <?= $form->field($model, 'title')->textInput([
                'placeholder' => 'Judul keunggulan...',
                'class' => 'form-control'
            ]) ?>

            <?= $form->field($model, 'subtitle')->textInput([
                'placeholder' => 'Deskripsi singkat...',
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
