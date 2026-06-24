<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\HomepageProduk[] $produks */

$this->title = 'Daftar Produk';
?>

<section class="produk mt-2" id="produk">

    <div class="produk-header-user">

        <h1 class="produk-title">
            Daftar Produk
        </h1>

    </div>

    <form id="filterForm" method="get">
        <div class="jenis-filter">

            <a href="<?= \yii\helpers\Url::to([
                'produk/index',
                'search' => Yii::$app->request->get('search'),
                'sort' => Yii::$app->request->get('sort')
            ]) ?>" class="jenis-btn <?= empty(Yii::$app->request->get('jenis')) ? 'active' : '' ?>">
                Semua
            </a>

            <?php foreach ($jenisList as $jenis): ?>

                <a href="<?= \yii\helpers\Url::to([
                    'produk/index',
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
                ['produk/index'],
                ['class' => 'btn btn-secondary']
            ) ?>

        </div>

    </form>



    <div class="produk-grid" id="produkGrid">

        <?= $this->render('_produk_grid', [
            'produks' => $produks,
            'pages' => $pages,
        ]) ?>

    </div>
</section>


<?php
$csrf = Yii::$app->request->csrfToken;
$addUrl = \yii\helpers\Url::to(['cart/add']);
$cartUrl = \yii\helpers\Url::to(['cart/index']);
$js = <<<JS
$(document).on('click', '.btn-add-cart', function() {

    let produkId = $(this).data('id');

    $.post('$addUrl', {
        produk_id: produkId,
        _csrf: '$csrf'
    }, function(res) {

        if(res.success) {

            $('#cart-count').text(res.count);

            let toastEl = document.getElementById('cartToast');

            if (toastEl) {
                let toast = new bootstrap.Toast(toastEl, {
                    delay: 2000
                });
                toast.show();
            }

        } else {

            alert('Gagal menambahkan produk ke keranjang');

        }

    });

});

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

$('#searchProduk').on('keyup', function() {

    clearTimeout(window.searchTimer);

    window.searchTimer = setTimeout(function() {

        loadProduk();

    }, 300);

});
JS;

$this->registerJs($js);
?>