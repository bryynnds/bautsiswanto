    <?php

use yii\helpers\Html;
?>

<div class="container mt-4">

    <h2 class="mb-4">Notifikasi</h2>

    <?php if (empty($notifications)): ?>

        <div class="alert alert-info">
            Belum ada notifikasi.
        </div>

    <?php else: ?>

        <?php foreach ($notifications as $notif): ?>

            <div class="card mb-3">

                <div class="card-body">

                    <h5>
                        <?= Html::encode($notif->title) ?>
                    </h5>

                    <p>
                        <?= Html::encode($notif->message) ?>
                    </p>

                    <small class="text-muted">
                        <?= date(
                            'd M Y H:i',
                            strtotime($notif->created_at)
                        ) ?>
                    </small>

                </div>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>