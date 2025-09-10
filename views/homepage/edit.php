<?php
use yii\bootstrap5\Tabs;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
?>

<div class="container mt-4">
    <h1>Edit Beranda</h1>

    <?= \yii\bootstrap5\Alert::widget() ?>

    <?= Tabs::widget([
        'items' => [
            [
                'label' => 'Hero',
                'content' => $this->render('_form_hero', ['model' => $hero]),
                'active' => true
            ],
            [
                'label' => 'Produk',
                'content' => $this->render('_form_produk', [
                    'model' => $newProduk,
                    'produks' => $produks
                ]),
            ],
            [
                'label' => 'Keunggulan',
                'content' => $this->render('_form_keunggulan', [
                    'model' => $newKeunggulan,
                    'keunggulans' => $keunggulans
                ]),
            ],
            [
                'label' => 'Testimoni',
                'content' => $this->render('_form_testimoni', [
                    'model' => $newTestimoni,
                    'testimonis' => $testimonis
                ]),
            ],
        ]
    ]) ?>
</div>
