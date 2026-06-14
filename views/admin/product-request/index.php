<?php

use yii\helpers\Html;

$this->title = 'Permintaan Produk';

?>

<div class="container mt-5">

    <div class="form-card">
        <div class="adminrequest-header">
            <h1 class="produk-title">
                Permintaan Produk
            </h1>
            <form id="filterForm">
                <div class="filter-toolbar">

                    <input type="text" id="searchRequest" name="search" class="filter-input"
                        placeholder="Cari produk, user, status..." value="<?= Yii::$app->request->get('search') ?>">

                    <select name="status" id="statusFilter" class="filter-select">

                        <option value="">
                            Semua Status
                        </option>

                        <option value="pending">
                            Pending
                        </option>

                        <option value="diproses">
                            Diproses
                        </option>

                        <option value="tersedia">
                            Tersedia
                        </option>

                        <option value="tidak_ditemukan">
                            Tidak Ditemukan
                        </option>

                    </select>

                    <select name="sort" id="sortFilter" class="filter-select">

                        <option value="latest">
                            Terbaru
                        </option>

                        <option value="oldest">
                            Terlama
                        </option>

                    </select>
                </div>
            </form>
        </div>


        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success">
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>

        <div class="request-info">
            Total Permintaan:
            <strong><?= $totalRequests ?></strong>
        </div>

        <?php if (!empty($requests)): ?>

            <div class="table-responsive" id="requestTable">

                <?= $this->render('_table', [
                    'requests' => $requests,
                    'pages' => $pages,
                ]) ?>

            </div>

        <?php else: ?>

            <div class="empty-request">

                <div class="empty-icon">
                    📦
                </div>

                <h4>Belum Ada Request Produk</h4>

                <p>
                    Request dari pengguna akan muncul di halaman ini.
                </p>

            </div>

        <?php endif; ?>

    </div>

</div>

<?php

$this->registerJs("
function loadRequests() {

    $.ajax({

        url: window.location.href.split('?')[0],

        type: 'GET',

        data: $('#filterForm').serialize(),

        success: function(response) {

            $('#requestTable').html(response);

        }

    });

}

$(document).on(
    'click',
    '.pagination a',
    function(e) {

        e.preventDefault();

        $.ajax({

            url: $(this).attr('href'),

            success: function(response) {

                $('#requestTable').html(response);

            }

        });

    }
);

$('#searchRequest').on('keyup', function() {

    clearTimeout(window.requestTimer);

    window.requestTimer = setTimeout(function() {

        loadRequests();

    }, 300);

});

$('#statusFilter').change(function() {

    loadRequests();

});

$('#sortFilter').change(function() {

    loadRequests();

});
");
?>

<style>
    .filter-box {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .filter-box input,
    .filter-box select {
        padding: 10px 14px;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    .filter-box input {
        min-width: 250px;
    }

    .request-info {
        background: #e0f7f6;
        color: #006666;
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .request-table-admin {
        width: 100%;
        border-collapse: collapse;
    }

    .request-table-admin th,
    .request-table-admin td {
        padding: 14px;
        border-bottom: 1px solid #e5e5e5;
    }

    .request-table-admin th {
        color: #006666;
        font-weight: 600;
        border-bottom: 2px solid #006666;
        background: #fff;
    }

    .request-table-admin tbody tr {
        transition: all .2s ease;
    }

    .request-table-admin tbody tr:hover {
        background: #f7fdfd;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .status-success {
        background: #d1fae5;
        color: #065f46;
    }

    .status-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .status-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-primary {
        background: #dbeafe;
        color: #1e40af;
    }

    .empty-request {
        text-align: center;
        padding: 50px 20px;
    }

    .empty-icon {
        font-size: 60px;
        margin-bottom: 15px;
    }

    .empty-request h4 {
        color: #006666;
        margin-bottom: 10px;
    }

    .empty-request p {
        color: #666;
    }
</style>