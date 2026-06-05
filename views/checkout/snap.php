<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= $clientKey ?>"></script>

<?php
$this->title = "Proses Pembayaran";
?>

<script>
    snap.pay('<?= $snapToken ?>', {

        onSuccess: function (result) {

            fetch('/checkout/paid?order_id=<?= $order->id ?>')
                .then(() => {
                    window.location.href = '/site/index?success=1';
                });
        },

        onPending: function (result) {

            window.location.href = '/site/index?pending=1';
        },

        onError: function (result) {

            window.location.href = '/site/index?error=1';
        },

        onClose: function () {

            window.location.href = '/user/profile?closed=1';
        }
    });
</script>