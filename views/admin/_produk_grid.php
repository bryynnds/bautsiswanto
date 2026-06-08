<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
?>

<?php foreach ($produk as $item): ?>

    <div class="card">

        <h3>
            <?= Html::encode($item->title) ?>
        </h3>

        <img src="<?= Yii::getAlias('@web') ?>/<?= Html::encode($item->image) ?>" alt="<?= Html::encode($item->title) ?>"
            class="produk-img">

        <div class="harga-box">

            <div>
                <small>Harga / Kg</small>
                <strong>
                    Rp
                    <?= number_format($item->harga_kg, 0, ',', '.') ?>
                </strong>
            </div>

            <div>
                <small>Harga / Biji</small>
                <strong>
                    Rp
                    <?= number_format($item->harga_bijian, 0, ',', '.') ?>
                </strong>
            </div>

        </div>

        <div class="btn-wrapper">

            <?= Html::a(
                'Ubah',
                ['homepage/update-produk', 'id' => $item->id],
                ['class' => 'btn-edit']
            ) ?>

            <?= Html::a(
                'Hapus',
                ['homepage/delete-produk', 'id' => $item->id],
                [
                    'class' => 'btn-hapus',
                    'data' => [
                        'confirm' => 'Yakin ingin menghapus produk ini?',
                        'method' => 'post',
                    ],
                ]
            ) ?>

        </div>

    </div>

<?php endforeach; ?>

<div class="produk-pagination">

    <?= LinkPager::widget([
        'pagination' => $pages,
    ]) ?>

</div>