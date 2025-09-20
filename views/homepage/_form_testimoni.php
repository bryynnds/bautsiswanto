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
<h3>Daftar Testimoni</h3>
<ul>
    <?php foreach ($testimonis as $t): ?>
        <li>
            "<?= $t->content ?>" <i>- <?= $t->author ?></i>
            <br>
            <?= Html::a('Edit', ['homepage/update-testimoni', 'id' => $t->id], ['class' => 'btn btn-sm btn-warning']) ?>
            <?= Html::a('Hapus', ['homepage/delete-testimoni', 'id' => $t->id], [
                'class' => 'btn btn-sm btn-danger',
                'data' => ['confirm' => 'Yakin hapus testimoni ini?']
            ]) ?>
        </li>
    <?php endforeach; ?>
</ul>