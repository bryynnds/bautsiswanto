<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use app\models\KategoriProduk;
use app\models\JenisProduk;

/** @var $model app\models\HomepageProduk */

if (!is_object($model)) {
    $model = new \app\models\HomepageProduk();
}

$this->title = 'Admin - Tambah Produk';

/*
|--------------------------------------------------------------------------
| Ambil Data Kategori
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

$jenisList = ArrayHelper::map(
    JenisProduk::find()->all(),
    'id',
    'nama_jenis'
);

?>

<div class="container mt-5">
    <div class="form-card">

        <h2 class="section-title mb-4">Tambah Produk</h2>

        <?php $form = ActiveForm::begin([
            'id' => 'form-produk',
            'action' => ['homepage/create-produk'],
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

        <!-- Jenis Produk -->
        <div class="mb-3">

            <label class="form-label">
                Jenis Produk
            </label>

            <select id="jenis-dropdown" class="form-control">

                <option value="">
                    Pilih jenis produk...
                </option>

                <?php foreach ($jenisList as $id => $nama): ?>

                    <option value="<?= $id ?>">
                        <?= $nama ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <!-- Kategori Produk -->
        <?= $form->field($model, 'kategori_id')->dropDownList(
            [],
            [
                'prompt' => 'Pilih kategori produk...',
                'class' => 'form-control',
            ]
        )->label('Kategori Produk') ?>

        <!-- Deskripsi -->
        <?= $form->field($model, 'description')->textarea([
            'rows' => 4,
            'placeholder' => 'Masukkan deskripsi produk...',
            'class' => 'form-control'
        ]) ?>

        <!-- Harga KG -->
        <?= $form->field($model, 'harga_kg')->textInput([
            'type' => 'number',
            'placeholder' => 'Masukkan harga per kg...',
            'min' => 0,
            'class' => 'form-control'
        ]) ?>

        <!-- Harga Bijian -->
        <?= $form->field($model, 'harga_bijian')->textInput([
            'type' => 'number',
            'placeholder' => 'Masukkan harga per bijian...',
            'min' => 0,
            'class' => 'form-control'
        ]) ?>

        <!-- Berat Produk -->
        <?= $form->field($model, 'berat')->textInput([
            'type' => 'number',
            'placeholder' => 'Masukkan berat per biji dalam gram...',
            'min' => 0,
            'class' => 'form-control'
        ]) ?>

        <!-- Upload Gambar -->
        <?= $form->field($model, 'image')->fileInput([
            'class' => 'form-control'
        ]) ?>

        <!-- Preview Gambar -->
        <?php if ($model->image): ?>
            <div class="mb-3 text-center">
                <img src="<?= Yii::getAlias('@web') . '/' . $model->image ?>" class="img-fluid rounded shadow-sm"
                    style="max-width:200px;">
            </div>
        <?php endif; ?>

        <!-- Tombol -->
        <div class="mt-4">
            <?= Html::submitButton('Tambah Produk', [
                'class' => 'btn btn-success me-2'
            ]) ?>

            <?= Html::a('Kembali', ['admin/produk'], [
                'class' => 'btn btn-secondary'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php

$urlKategori = \yii\helpers\Url::to(['homepage/get-kategori']);

$this->registerJs("
    $('#form-produk').on('afterValidate', function (e, messages, errorAttributes) {

        if (errorAttributes.length > 0) {

            // Hapus alert lama
            $('.custom-alert').remove();

            // Buat alert
            var alertBox = $('<div class=\"alert alert-danger custom-alert\">Mohon lengkapi data produk dengan benar.</div>');

            // Letakkan setelah judul
            $('.section-title').after(alertBox);

            // Scroll ke atas
            $('html, body').animate({
                scrollTop: $('.form-card').offset().top - 20
            }, 500);
        }
    });
    
    // Ambil kategori berdasarkan jenis
    $('#jenis-dropdown').on('change', function () {

        let jenisId = $(this).val();

        $('#homepageproduk-kategori_id').html(
            '<option value=\"\">Loading...</option>'
        );

        $.get(
            '$urlKategori',
            {
                jenis_id: jenisId
            },
            function (data) {

                $('#homepageproduk-kategori_id').html(data);

            }
        );

    });

    // Validasi Form
    $('#form-produk').on('afterValidate', function (e, messages, errorAttributes) {

        if (errorAttributes.length > 0) {

            $('.custom-alert').remove();

            var alertBox = $('<div class=\"alert alert-danger custom-alert\">Mohon lengkapi data produk dengan benar.</div>');

            $('.section-title').after(alertBox);

            $('html, body').animate({
                scrollTop: $('.form-card').offset().top - 20
            }, 500);
        }
    });

");
?>