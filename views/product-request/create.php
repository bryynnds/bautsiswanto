<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\JenisProduk;

$this->title = "Ajukan Permintaan Produk";
?>

<div class="container mt-5">

    <div class="form-card">

        <h2 class="section-title mb-3">
            Ajukan Permintaan Produk
        </h2>

        <div class="request-info">

            Tidak menemukan produk yang dicari?

            <br>

            Silakan ajukan permintaan produk dan admin akan
            meninjau permintaan Anda.

        </div>

        <?php $form = ActiveForm::begin([
            'options' => [
                'enctype' => 'multipart/form-data'
            ]
        ]); ?>

        <?= $form->field($model, 'nama_produk')
            ->textInput([
                'placeholder' => 'Contoh: Baut M12 Stainless'
            ]) ?>

        <?= $form->field($model, 'jenis_produk_id')
            ->dropDownList(
                ArrayHelper::map(
                    JenisProduk::find()->all(),
                    'id',
                    'nama_jenis'
                ),
                [
                    'prompt' => 'Pilih Jenis Produk'
                ]
            ) ?>

        <?= $form->field($model, 'material')
            ->textInput([
                'placeholder' => 'Contoh: Stainless Steel'
            ]) ?>

        <?= $form->field($model, 'keterangan')
            ->textarea([
                'rows' => 5,
                'placeholder' =>
                    'Jelaskan spesifikasi produk yang dibutuhkan'
            ]) ?>

        <?= $form->field($model, 'foto')
            ->fileInput([
                'class' => 'form-control'
            ]) ?>

        <div class="btn-group-custom">

            <?= Html::submitButton(
                'Kirim Request',
                ['class' => 'btn btn-primary']
            ) ?>

            <?= Html::a(
                'Kembali',
                ['index'],
                ['class' => 'btn btn-secondary']
            ) ?>

        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>

<style>
    .request-info {
        background: #e0f7f6;
        color: #006666;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 25px;
        line-height: 1.7;
    }

    .btn-group-custom {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .form-card .form-control,
    .form-card .form-select {
        border-radius: 8px;
    }

    .form-card label {
        font-weight: 600;
        color: #006666;
    }
</style>