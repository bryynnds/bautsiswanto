<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Tentang Kami';
// $this->params['breadcrumbs'][] = $this->title;
?>

<div class="container mt-5">
    <div class="form-card text-center">
        <h2 class="section-title mb-3"><?= Html::encode($this->title) ?></h2>
        <p class="section-subtitle mb-4">
            Halaman ini berisi informasi tentang aplikasi/website ini. 
            Anda dapat menyesuaikan kontennya sesuai kebutuhan.
        </p>

        <div class="about-content text-muted">
            <p>
                File ini dapat dimodifikasi untuk menambahkan profil perusahaan, 
                visi & misi, atau detail lainnya yang ingin ditampilkan.
            </p>
        </div>
    </div>
</div>
