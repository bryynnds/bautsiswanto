<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = "Ubah Password";
?>

<div class="container mt-5">
    <div class="form-card">
        <h2 class="section-title mb-3 text-center"><?= Html::encode($this->title) ?></h2>

        <?php $form = ActiveForm::begin(['options' => ['class' => 'form-styled']]); ?>

            <?= $form->field($user, 'username')->textInput([
                'placeholder' => 'Username',
                'class' => 'form-control'
            ]) ?>

            <?= $form->field($user, 'old_password')->passwordInput([
                'placeholder' => 'Password lama',
                'class' => 'form-control'
            ])->label('Password Lama (isi jika ingin mengganti password)') ?>

            <?= $form->field($user, 'new_password')->passwordInput([
                'placeholder' => 'Password baru',
                'class' => 'form-control'
            ])->label('Password Baru (opsional)') ?>

            <div class="mt-4 text-center">
                <?= Html::submitButton('Simpan Perubahan', ['class' => 'btn btn-primary px-4']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
