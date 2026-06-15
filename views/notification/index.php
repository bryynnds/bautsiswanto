<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Notifikasi';

?>

<div class="container mt-5">

    <div class="form-card">

        <div class="adminrequest-header">

            <div>

                <h1 class="produk-title">
                    Riwayat Notifikasi
                </h1>

            </div>

            <?php if (!empty($notifications)): ?>

                <button id="markAllReadPageBtn" class="btn btn-success mb-3">

                    <i class="bi bi-check2-all"></i>
                    Tandai Semua Dibaca

                </button>

            <?php endif; ?>

        </div>

        <div class="request-info">

            Total Notifikasi:
            <strong><?= $totalNotifications ?></strong>

        </div>

        <?php if (empty($notifications)): ?>

            <div class="empty-request">

                <div class="empty-icon">
                    🔔
                </div>

                <h4>
                    Belum Ada Notifikasi
                </h4>

                <p>
                    Semua aktivitas pesanan akan muncul di halaman ini.
                </p>

            </div>

        <?php else: ?>

            <?php foreach ($notifications as $notif): ?>

                <div class="notification-card <?= !$notif->is_read ? 'unread' : '' ?>">

                    <div class="notification-content">

                        <div class="notification-header">

                            <h5>

                                <?= Html::encode($notif->title) ?>

                                <?php if (!$notif->is_read): ?>

                                    <span class="new-badge">
                                        Baru
                                    </span>

                                <?php endif; ?>

                            </h5>

                            <span class="notification-time">

                                <?= date(
                                    'd M Y H:i',
                                    strtotime($notif->created_at)
                                ) ?>

                            </span>

                        </div>

                        <p>

                            <?= Html::encode($notif->message) ?>

                        </p>

                    </div>

                </div>

            <?php endforeach; ?>

            <div class="pagination-wrapper">

                <?= LinkPager::widget([
                    'pagination' => $pages,
                ]) ?>

            </div>

        <?php endif; ?>

    </div>

</div>

<?php

$this->registerJs("
$('#markAllReadPageBtn').click(function() {

    $.ajax({

        url: '" . Url::to(['/notification/read-all']) . "',

        type: 'POST',

        data: {
            _csrf: yii.getCsrfToken()
        },

        success: function(response) {

            if(response.success){

                location.reload();

            }

        }

    });

});
");
?>

<style>
    .request-info {
        background: #e0f7f6;
        color: #006666;
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .notification-card {

        background: #fff;
        border-radius: 12px;
        padding: 18px 22px;
        margin-bottom: 15px;
        border: 1px solid #e5e7eb;
        transition: all .2s ease;

    }

    .notification-card:hover {

        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, .08);

    }

    .notification-card.unread {

        background: #eef8ff;
        border-left: 5px solid #0d6efd;

    }

    .notification-header {

        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        gap: 15px;

    }

    .notification-header h5 {

        margin: 0;
        font-size: 17px;
        font-weight: 600;
        color: #006666;

    }

    .notification-time {

        font-size: 12px;
        color: #6b7280;
        white-space: nowrap;

    }

    .notification-content p {

        margin: 0;
        color: #555;
        line-height: 1.6;

    }

    .new-badge {

        background: #2563eb;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        margin-left: 8px;
        font-weight: 600;

    }

    .pagination-wrapper {

        margin-top: 25px;
        display: flex;
        justify-content: center;

    }

    @media (max-width: 768px) {

        .notification-header {

            flex-direction: column;
            align-items: flex-start;

        }

        .notification-time {

            margin-top: 5px;

        }

    }
</style>