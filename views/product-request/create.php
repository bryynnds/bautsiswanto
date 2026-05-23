<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\JenisProduk;
?>

<div class="container mt-4">

    <h2>Request Produk</h2>

    <p>
        Tidak menemukan produk yang dicari?
        Silakan ajukan request produk.
    </p>

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'nama_produk')->textInput() ?>

    <?= $form->field($model, 'jenis_produk_id')->dropDownList(
        ArrayHelper::map(
            JenisProduk::find()->all(),
            'id',
            'nama_jenis'
        ),
        ['prompt' => 'Pilih Jenis Produk']
    ) ?>

    <?= $form->field($model, 'material')->textInput() ?>

    <?= $form->field($model, 'keterangan')->textarea([
        'rows' => 5
    ]) ?>

    <?= $form->field($model, 'foto')->fileInput() ?>

    <div class="form-group mt-3">
        <?= Html::submitButton(
            'Kirim Request',
            ['class' => 'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>