<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use app\models\JenisProduk;

/** @var $model app\models\KategoriProduk */

$this->title = 'Tambah Kategori';

/*
|--------------------------------------------------------------------------
| Dropdown Jenis Produk
|--------------------------------------------------------------------------
*/

$jenisList = ArrayHelper::map(
    JenisProduk::find()->all(),
    'id',
    'nama_jenis'
);

?>

<div class="container mt-5">

    <div class="form-card">

        <h2 class="section-title mb-4">
            Tambah Kategori Produk
        </h2>

        <?php $form = ActiveForm::begin([

            'options' => [
                'class' => 'form-styled'
            ]

        ]); ?>

        <!-- Jenis Produk -->
        <?= $form->field($model, 'jenis_id')->dropDownList(
            $jenisList,
            [
                'prompt' => 'Pilih jenis produk...',
                'class' => 'form-control'
            ]
        ) ?>

        <!-- Nama Kategori -->
        <?= $form->field($model, 'nama_kategori')->textInput([
            'placeholder' => 'Contoh: JP, Hex, Fisher...',
            'class' => 'form-control'
        ]) ?>

        <!-- Tombol -->
        <div class="mt-4">

            <?= Html::submitButton(
                'Simpan Kategori',
                [
                    'class' => 'btn btn-success me-2'
                ]
            ) ?>

            <?= Html::a(
                'Kembali',
                ['admin/produk'],
                [
                    'class' => 'btn btn-secondary'
                ]
            ) ?>

        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>