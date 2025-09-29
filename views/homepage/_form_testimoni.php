<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var $model app\models\HomepageTestimoni */
/** @var $testimonis app\models\HomepageTestimoni[] */

// Ensure $model is an object, not an array
if (!is_object($model)) {
    $model = new \app\models\HomepageTestimoni();
}
?>

<!-- <h3>Tambah Testimoni</h3> -->
<?php $form = ActiveForm::begin([
    'action' => ['homepage/create-testimoni']
]); ?>

<?= $form->field($model, 'author')->textInput([
    'placeholder' => 'Ketik disini...',
]) ?>
<?= $form->field($model, 'content')->textarea([
    'placeholder' => 'Ketik disini...',
]) ?>
<?= Html::submitButton('Tambah Testimoni', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>

<hr>
<div class="promo-card">
    <h3>Daftar Testimoni</h3>
    <table class="cart-table">
        <thead>
            <tr>
                <th>Isi Testimoni</th>
                <th>Penulis</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($testimonis as $t): ?>
                <tr>
                    <td>"<?= Html::encode($t->content) ?>"</td>
                    <td><i><?= Html::encode($t->author) ?></i></td>
                    <td>
                        <div class="btn-wrapper">
                            <?= Html::a('Edit', ['homepage/update-testimoni', 'id' => $t->id], ['class' => 'btn-edit']) ?>
                            <?= Html::a('Hapus', ['homepage/delete-testimoni', 'id' => $t->id], [
                                'class' => 'btn-hapus',
                                'data' => ['confirm' => 'Yakin hapus testimoni ini?']
                            ]) ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>