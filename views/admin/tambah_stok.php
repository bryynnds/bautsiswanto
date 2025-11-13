<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var app\models\HomepageProduk[] $produk */
$this->title = 'Tambah / Ubah Stok';
?>

<div class="container mt-4">
    <h2 class="mb-4"><?= Html::encode($this->title) ?></h2>

    <form method="post" action="<?= Url::to(['homepage/simpan-stok']) ?>">
        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>

        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>Nama Produk</th>
                    <th>Merek</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produk as $item): ?>
                    <tr>
                        <td><?= Html::encode($item->title) ?></td>
                        <td><?= Html::encode($item->brand_name) ?></td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                <button type="button" class="btn btn-sm btn-danger btn-minus me-2" data-id="<?= $item->id ?>">−</button>
                                <input type="number" name="stok[<?= $item->id ?>]"
                                    value="<?= Html::encode($item->stok) ?>"
                                    class="form-control text-center"
                                    style="width: 80px;">
                                <button type="button" class="btn btn-sm btn-success btn-plus ms-2" data-id="<?= $item->id ?>">+</button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-end mt-3">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="<?= Url::to(['admin/produk']) ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<style>
    .table th,
    .table td {
        vertical-align: middle;
    }
</style>

<?php
$js = <<<JS
$(document).on('click', '.btn-minus', function() {
    let input = $(this).closest('td').find('input[type=number]');
    let val = parseInt(input.val()) || 0;
    if (val > 0) input.val(val - 1);
});

$(document).on('click', '.btn-plus', function() {
    let input = $(this).closest('td').find('input[type=number]');
    let val = parseInt(input.val()) || 0;
    input.val(val + 1);
});
JS;
$this->registerJs($js);
?>