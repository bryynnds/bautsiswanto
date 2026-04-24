<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var $model app\models\HomepageTestimoni */
/** @var $testimonis app\models\HomepageTestimoni[] */

if (!is_object($model)) {
    $model = new \app\models\HomepageTestimoni();
}
?>

<div class="form-card">
    <h2 class="section-title mb-4">Tambah Testimoni</h2>

    <?php $form = ActiveForm::begin([
        'id' => 'form-testimoni', // ✅ WAJIB
        'action' => ['homepage/create-testimoni'],
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
    ]); ?>

    <?= $form->field($model, 'author')->textInput([
        'placeholder' => 'Ketik disini...',
    ]) ?>

    <?= $form->field($model, 'content')->textarea([
        'placeholder' => 'Ketik disini...',
    ]) ?>

    <?= Html::submitButton('Tambah Testimoni', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>
</div>

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

<?php
$this->registerJs("
    $('#form-testimoni').on('afterValidate', function (e, messages, errorAttributes) {
        if (errorAttributes.length > 0) {

            $('.custom-alert').remove();

            var alertBox = $('<div class=\"alert alert-danger custom-alert\">Mohon lengkapi data testimoni dengan benar.</div>');

            $('.form-card .section-title').after(alertBox);

            $('html, body').animate({
                scrollTop: $('.form-card').offset().top - 20
            }, 500);

            $('.has-error textarea, .has-error input').first().focus();
        }
    });
");
?>