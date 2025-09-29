<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var $model app\models\HomepageKeunggulan */
/** @var $keunggulans app\models\HomepageKeunggulan[] */

// Ensure $model is an object of HomepageKeunggulan
if (!is_object($model)) {
    $model = new \app\models\HomepageKeunggulan();
}
?>

<!-- <h3>Tambah Keunggulan</h3> -->
<?php $form = ActiveForm::begin([
    'action' => ['homepage/create-keunggulan']
]); ?>
<?= $form->field($model, 'title')->textInput(
    [
        'placeholder' => 'Ketik disini...',
    ]
) ?>
<?= $form->field($model, 'subtitle')->textInput(
    [
        'placeholder' => 'Ketik disini...',
    ]
) ?>
<?= Html::submitButton('Tambah Keunggulan', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>

<hr>
<div class="promo-card">
    <h3>Daftar Keunggulan</h3>
    <table class="cart-table">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Subjudul</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($keunggulans as $k): ?>
                <tr>
                    <td><?= Html::encode($k->title) ?></td>
                    <td><?= Html::encode($k->subtitle) ?></td>
                    <td>
                        <div class="btn-wrapper">
                            <?= Html::a('Edit', ['homepage/update-keunggulan', 'id' => $k->id], ['class' => 'btn-edit']) ?>
                            <?= Html::a('Hapus', ['homepage/delete-keunggulan', 'id' => $k->id], [
                                'class' => 'btn-hapus',
                                'data' => ['confirm' => 'Yakin hapus keunggulan ini?']
                            ]) ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>