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
            'brandLabel' => Html::img(
                '@web/images/logo121.png', // path logo di folder web/images
                ['alt' => 'CuanKonek.id', 'style' => 'height:40px;'] // bisa diatur ukuran
            ),
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
                ['label' => 'Produk', 'url' => ['/produk/index']],
                ['label' => 'Kontak', 'url' => ['/site/contact']],
                ['label' => 'Tentang', 'url' => ['/site/about']],
                ['label' => 'Masuk', 'url' => ['/site/login']],
            ];
        } elseif (Yii::$app->user->identity->isAdmin()) {
            $menuItems = [
                ['label' => 'Beranda', 'url' => ['/admin/dashboard']],
                ['label' => 'Produk', 'url' => ['/homepage/admin-produk']],
                ['label' => 'Kasir', 'url' => ['/admin/calculator']],
                ['label' => 'Riwayat Belanja', 'url' => ['/admin/history']],
                ['label' => 'CMS Beranda', 'url' => ['/homepage/edit']],
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
                ['label' => 'Produk', 'url' => ['/produk/index']],
                ['label' => 'Kontak', 'url' => ['/site/contact']],
                ['label' => 'Tentang', 'url' => ['/site/about']],
                ['label' => 'Keranjang', 'url' => ['/cart/index']],

                // Dropdown manual bootstrap
                '<li class="nav-item dropdown" style="position: relative; list-style: none;">
    <a href="#" id="accountToggle" class="nav-link">
        Akun (' . Yii::$app->user->identity->username . ') ▼
    </a>

    <div id="accountMenu" 
        style="
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border-radius: 8px;
            padding: 10px 0;
            width: 160px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            text-align: left;
            z-index: 999;
        ">
        <a href="/user/profile" class="dropdown-item" style="padding: 10px 20px; display:block;">Profil</a>

        <form action="/site/logout" method="post" style="margin:0;">
            <input type="hidden" name="_csrf" value="' . Yii::$app->request->csrfToken . '">
            <button type="submit" class="dropdown-item text-danger" 
                style="padding: 10px 20px; width: 100%; text-align:left; border:none; background:none;">
                Logout
            </button>
        </form>
    </div>
</li>',
            ];
        }



        echo Nav::widget([
            'options' => ['class' => 'navbar-nav ms-auto'],
            'items' => $menuItems,
            'encodeLabels' => false, // ← WAJIB
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
        <p>© 2025 CuanKonek.id</p>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggle = document.getElementById("accountToggle");
            const menu = document.getElementById("accountMenu");

            toggle.addEventListener("click", function(e) {
                e.preventDefault();
                menu.style.display = (menu.style.display === "none" || menu.style.display === "") ?
                    "block" :
                    "none";
            });

            document.addEventListener("click", function(e) {
                if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                    menu.style.display = "none";
                }
            });
        });
    </script>


</body>

</html>
<?php $this->endPage() ?>