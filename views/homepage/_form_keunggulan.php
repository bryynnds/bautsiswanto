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

<h3>Tambah Keunggulan</h3>
<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'subtitle')->textInput() ?>
    <?= Html::submitButton('Tambah Keunggulan', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>

<hr>
<h3>Daftar Keunggulan</h3>
<ul>
<?php foreach ($keunggulans as $k): ?>
    <li>
        <b><?= $k->title ?></b> - <?= $k->subtitle ?>
        <br>
        <?= Html::a('Edit', ['homepage/update-keunggulan', 'id' => $k->id], ['class' => 'btn btn-sm btn-warning']) ?>
        <?= Html::a('Hapus', ['homepage/delete-keunggulan', 'id' => $k->id], [
            'class' => 'btn btn-sm btn-danger',
            'data' => ['confirm' => 'Yakin hapus keunggulan ini?']
        ]) ?>
    </li>
<?php endforeach; ?>
</ul>

