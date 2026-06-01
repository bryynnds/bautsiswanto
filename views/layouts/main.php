<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

use app\models\Notification;

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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

        $notifications = [];
        $unreadCount = 0;

        if (!Yii::$app->user->isGuest) {

            $notifications = Notification::find()
                ->where([
                    'user_id' => Yii::$app->user->id
                ])
                ->orderBy(['id' => SORT_DESC])
                ->limit(3)
                ->all();

            $unreadCount = Notification::find()
                ->where([
                    'user_id' => Yii::$app->user->id,
                    'is_read' => 0
                ])
                ->count();
        }

        // Tentukan menu sesuai kondisi
        if (Yii::$app->user->isGuest) {
            $menuItems = [
                ['label' => 'Beranda', 'url' => ['/site/index']],
                ['label' => 'Produk', 'url' => ['/produk/index']],
                ['label' => 'Keranjang', 'url' => ['/cart/index']],
                ['label' => 'Masuk', 'url' => ['/site/login']],
            ];
        } elseif (Yii::$app->user->identity->isAdmin()) {
            $menuItems = [
                ['label' => 'Beranda', 'url' => ['/admin/dashboard']],
                ['label' => 'Produk', 'url' => ['/homepage/admin-produk']],
                ['label' => 'Permintaan Produk', 'url' => ['/admin-product-request/index']],
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
                ['label' => 'Permintaan Produk', 'url' => ['product-request/index']],
                ['label' => 'Keranjang', 'url' => ['/cart/index']],

                '<li class="nav-item dropdown" style="position: relative; list-style:none;">

    <a href="#" id="notifToggle" class="nav-link position-relative">

        <i class="bi bi-bell" style="font-size: 20px;"></i>

        ' . ($unreadCount > 0
                    ? '<span id="notifBadge"
                style="
                    position:absolute;
                    top:5px;
                    right:0;
                    background:red;
                    color:white;
                    border-radius:50%;
                    padding:2px 6px;
                    font-size:10px;
                ">
                    ' . $unreadCount . '
               </span>'
                    : '') . '

    </a>

    <div id="notifMenu"
        style="
            display:none;
            position:absolute;
            right:0;
            top:100%;
            background:#fff;
            border-radius:14px;
            width:360px;
            border:1px solid #e9ecef;
            max-height:400px;
            overflow-y:auto;
            box-shadow:0 4px 20px rgba(0,0,0,0.15);
            z-index:999;
        ">

        <div style="
    padding:15px;
    border-bottom:1px solid #eee;
    font-weight:600;
    font-size:16px;
    background:#f8f9fa;
    border:1px solid #e5e7eb;
    border-radius:14px 14px 0 0;
    overflow:hidden;
">
    Notifikasi
</div>

        ' .

                (
                    empty($notifications)

                    ?

                    '<div style="padding:15px; color:gray;">
                Belum ada notifikasi
            </div>'

                    :

                    implode("", array_map(function ($notif) {

                        $bgColor = $notif->is_read
                            ? '#ffffff'
                            : '#eef4ff';

                        return \yii\helpers\Html::a(

                            '
        <div style="
            padding:14px 16px;
            border-bottom:1px solid #f1f1f1;
            background:' . $bgColor . ';
            transition:0.2s;
        ">

            <div style="
                font-weight:600;
                font-size:14px;
                color:#222;
                margin-bottom:6px;
            ">
                ' . $notif->title . '
            </div>

            <div style="
                font-size:13px;
                color:#666;
                line-height:1.5;
            ">
                ' . $notif->message . '
            </div>

            <div style="
                font-size:11px;
                color:#999;
                margin-top:8px;
            ">
                ' . date('d M Y H:i', strtotime($notif->created_at)) . '
            </div>

        </div>
        ',

                            ['/produk/index'],

                            [
                                'style' => '
                text-decoration:none;
                color:inherit;
                display:block;
            ',

                                'onmouseover' =>
                                    "this.firstElementChild.style.background='#f5f7fa'",

                                'onmouseout' =>
                                    "this.firstElementChild.style.background='" . $bgColor . "'",
                            ]
                        );

                    }, $notifications))
                )

                . '

    </div>

</li>',

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
        <div id="cartToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Produk berhasil ditambahkan ke keranjang!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>


    <?php $this->endBody() ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const toggle = document.getElementById("accountToggle");
            const menu = document.getElementById("accountMenu");
            const notifToggle = document.getElementById("notifToggle");
            const notifMenu = document.getElementById("notifMenu");

            // Dropdown akun
            if (toggle && menu) {

                toggle.addEventListener("click", function (e) {

                    e.preventDefault();

                    menu.style.display =
                        (menu.style.display === "none" || menu.style.display === "")
                            ? "block"
                            : "none";

                });

                document.addEventListener("click", function (e) {

                    if (
                        !toggle.contains(e.target) &&
                        !menu.contains(e.target)
                    ) {
                        menu.style.display = "none";
                    }

                });

            }

            // Dropdown notifikasi
            if (notifToggle && notifMenu) {

                notifToggle.addEventListener("click", function (e) {

                    e.preventDefault();

                    notifMenu.style.display =
                        (notifMenu.style.display === "none" ||
                            notifMenu.style.display === "")
                            ? "block"
                            : "none";

                    const notifBadge =
                        document.getElementById("notifBadge");

                    if (notifBadge) {
                        notifBadge.remove();
                    }

                    fetch("/notification/read-all", {
                        method: "POST",
                        headers: {
                            "X-CSRF-Token": yii.getCsrfToken()
                        }
                    });

                });

                document.addEventListener("click", function (e) {

                    if (
                        !notifToggle.contains(e.target) &&
                        !notifMenu.contains(e.target)
                    ) {
                        notifMenu.style.display = "none";
                    }

                });

            }

        });
    </script>


</body>

</html>
<?php $this->endPage() ?>