<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div>

    <h2 class="section-title mb-4">Produk Terlaris</h2>

    <?php $form = ActiveForm::begin([
        'action' => ['homepage/update-produk-terlaris']
    ]); ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'jumlah_tampil')
        ->input('number', [
            'min' => 1,
            'max' => 12
        ]) ?>

    <div class="mt-3">
        <?= Html::submitButton(
            'Simpan',
            ['class' => 'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>