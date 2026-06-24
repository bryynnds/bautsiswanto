<?php

/** @var yii\web\View $this */
/** @var app\models\HomepageProduk[] $produk */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Daftar Produk';
?>

<section class="produk mt-2" id="produk">

    <div class="produk-header">

        <h1 class="produk-title">
            Daftar Produk
        </h1>

        <div class="produk-action">

            <a href="<?= Url::to(['homepage/create-produk']) ?>" class="btn btn-primary">
                + Tambah Produk
            </a>

            <a href="<?= Url::to(['homepage/create-kategori']) ?>" class="btn btn-primary">
                + Tambah Kategori
            </a>

        </div>

    </div>


    <form id="filterForm" method="get">
        <div class="jenis-filter">

            <a href="<?= \yii\helpers\Url::to([
                'homepage/admin-produk',
                'search' => Yii::$app->request->get('search'),
                'sort' => Yii::$app->request->get('sort')
            ]) ?>" class="jenis-btn <?= empty(Yii::$app->request->get('jenis')) ? 'active' : '' ?>">
                Semua
            </a>

            <?php foreach ($jenisList as $jenis): ?>

                <a href="<?= \yii\helpers\Url::to([
                    'homepage/admin-produk',
                    'jenis' => $jenis->id,
                    'search' => Yii::$app->request->get('search'),
                    'sort' => Yii::$app->request->get('sort')
                ]) ?>" class="jenis-btn <?= Yii::$app->request->get('jenis') == $jenis->id ? 'active' : '' ?>">
                    <?= $jenis->nama_jenis ?>
                </a>

            <?php endforeach; ?>

        </div>

        <div class="filter-toolbar">

            <input type="text" name="search" id="searchProduk" class="filter-input" placeholder="Cari produk..."
                value="<?= Yii::$app->request->get('search') ?>">

            <select name="kategori" id="kategoriFilter" class="filter-select">

                <option value="">
                    <?= $jenisAktif
                        ? 'Pilih Kategori ' . $jenisAktif->nama_jenis
                        : 'Semua Kategori' ?>
                </option>

                <?php foreach ($kategoriList as $kategori): ?>
                    <option value="<?= $kategori->id ?>" <?= Yii::$app->request->get('kategori') == $kategori->id ? 'selected' : '' ?>>
                        <?= $kategori->nama_kategori ?>
                    </option>
                <?php endforeach; ?>

            </select>

            <select name="sort" id="sortFilter" class="filter-select">

                <option value="">Urutkan</option>
                <option value="harga_asc">Harga Termurah</option>
                <option value="harga_desc">Harga Termahal</option>

            </select>

            <?= Html::a(
                'Reset',
                ['admin/produk'],
                ['class' => 'btn btn-secondary']
            ) ?>

        </div>

    </form>


    <div class="produk-grid" id="produkGrid">

        <?= $this->render('_produk_grid', [
            'produk' => $produk,
            'pages' => $pages,
        ]) ?>

    </div>

</section>

<?php

$this->registerJs("

$('#kategoriFilter').change(function() {
    loadProduk();
});

$('#sortFilter').change(function() {
    loadProduk();
});

function loadProduk() {

    $.ajax({

        url: window.location.href.split('?')[0],

        type: 'GET',

        data: $('#filterForm').serialize(),

        success: function(response) {

            $('#produkGrid').html(response);

        }

    });

}
    
$(document).on(
    'click',
    '.pagination a',
    function(e) {

        e.preventDefault();

        $.ajax({

            url: $(this).attr('href'),

            success: function(response) {

                $('#produkGrid').html(response);

            }

        });

    }
);

$('#searchProduk').on('keyup', function() {

    clearTimeout(window.searchTimer);

    window.searchTimer = setTimeout(function() {

        loadProduk();

    }, 300);

});
");
?>