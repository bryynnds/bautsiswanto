<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- Bootstrap JS Bundle (sudah termasuk Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header id="header">
        <?php
        NavBar::begin([
            'brandLabel' => "Wardah Cosmetics",
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar navbar-expand-md fixed-top custom-navbar'
            ],
            'containerOptions' => [
                'class' => 'container-fluid'
            ],
        ]);

        // Tentukan menu sesuai kondisi
        if (Yii::$app->user->isGuest) {
            $menuItems = [
                ['label' => 'Beranda', 'url' => ['/site/index']],
                ['label' => 'Kontak', 'url' => ['/site/contact']],
                ['label' => 'Tentang', 'url' => ['/site/about']],
                ['label' => 'Masuk', 'url' => ['/site/login']],
            ];
        } elseif (Yii::$app->user->identity->isAdmin()) {
            $menuItems = [
                ['label' => 'Ubah Beranda', 'url' => ['/homepage/edit']],
                '<li class="nav-item">'
                    . Html::beginForm(['/site/logout'])
                    . Html::submitButton(
                        'Keluar (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'nav-link btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'
            ];
        } else {
            // untuk user biasa
            $menuItems = [
                ['label' => 'Beranda', 'url' => ['/site/index']],
                ['label' => 'Kontak', 'url' => ['/site/contact']],
                ['label' => 'Tentang', 'url' => ['/site/about']],
                ['label' => 'Keranjang', 'url' => ['/cart/index']],
                '<li class="nav-item">'
                    . Html::beginForm(['/site/logout'])
                    . Html::submitButton(
                        'Logout (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'nav-link btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'
            ];
        }


        echo Nav::widget([
            'options' => ['class' => 'navbar-nav ms-auto'],
            'items' => $menuItems,
        ]);
        NavBar::end();
        ?>
    </header>

    <main id="main" class="flex-shrink-0" role="main">
        <div class="container-fluid p-0">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            <?php endif ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer id="kontak" class="mt-auto text-center py-3">
        <p>© 2025 Wardah Cosmetics</p>
        <p>Ikuti kami di
            <a href="#">Instagram</a> |
            <a href="#">Facebook</a> |
            <a href="#">Twitter</a>
        </p>
    </footer>

    <div class="toast-container position-fixed bottom-0 start-0 p-3">
        <div id="cartToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Produk berhasil ditambahkan ke keranjang!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>


    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>