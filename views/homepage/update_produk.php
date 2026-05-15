<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use app\models\KategoriProduk;

/** @var app\models\HomepageProduk $model */

$this->title = 'Admin - Ubah Produk';

/*
|--------------------------------------------------------------------------
| Dropdown Kategori
|--------------------------------------------------------------------------
*/

$kategoriList = ArrayHelper::map(
    KategoriProduk::find()
        ->with('jenis')
        ->all(),
    'id',
    function ($model) {
        return $model->jenis->nama_jenis . ' - ' . $model->nama_kategori;
    }
);

?>

<div class="container mt-5">

    <div class="form-card">

        <h2 class="section-title mb-4">
            Ubah Produk
        </h2>

        <?php $form = ActiveForm::begin([

            'id' => 'form-produk-update',

            'options' => [
                'class' => 'form-styled',
                'enctype' => 'multipart/form-data'
            ],

            'enableClientValidation' => true,
            'enableAjaxValidation' => false,

        ]); ?>

        <!-- Nama Produk -->
        <?= $form->field($model, 'title')->textInput([
            'placeholder' => 'Ketik nama produk...',
            'class' => 'form-control'
        ]) ?>

        <!-- Kategori Produk -->
        <?= $form->field($model, 'kategori_id')->dropDownList(
            $kategoriList,
            [
                'prompt' => 'Pilih kategori produk...',
                'class' => 'form-control'
            ]
        ) ?>

        <!-- Deskripsi -->
        <?= $form->field($model, 'description')->textarea([
            'rows' => 4,
            'placeholder' => 'Ketik deskripsi produk...',
            'class' => 'form-control'
        ]) ?>

        <!-- Harga KG -->
        <?= $form->field($model, 'harga_kg')->textInput([
            'type' => 'number',
            'placeholder' => 'Masukkan harga per kg',
            'class' => 'form-control'
        ]) ?>

        <!-- Harga Bijian -->
        <?= $form->field($model, 'harga_bijian')->textInput([
            'type' => 'number',
            'placeholder' => 'Masukkan harga per bijian',
            'class' => 'form-control'
        ]) ?>

        <!-- Upload Gambar -->
        <?= $form->field($model, 'image')->fileInput([
            'class' => 'form-control'
        ]) ?>

        <!-- Preview Gambar -->
        <?php if ($model->image): ?>

            <div class="mb-3 text-center">

                <img
                    src="<?= Yii::getAlias('@web') . '/' . $model->image ?>"
                    class="img-fluid rounded shadow-sm"
                    style="max-width:200px;"
                >

            </div>

        <?php endif; ?>

        <!-- Tombol -->
        <div class="mt-4">

            <?= Html::submitButton(
                'Simpan Perubahan',
                [
                    'class' => 'btn btn-primary me-2'
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

<?php

$this->registerJs("

    $('#form-produk-update').on('afterValidate', function (e, messages, errorAttributes) {

        if (errorAttributes.length > 0) {

            // Hapus alert lama
            $('.custom-alert').remove();

            // Buat alert
            var alertBox = $('<div class=\"alert alert-danger custom-alert\">Mohon lengkapi data produk dengan benar.</div>');

            // Letakkan di bawah judul
            $('.form-card .section-title').after(alertBox);

            // Scroll ke atas
            $('html, body').animate({
                scrollTop: $('.form-card').offset().top - 20
            }, 500);

            // Fokus field error pertama
            $('.has-error input, .has-error textarea').first().focus();
        }
    });

");

?>