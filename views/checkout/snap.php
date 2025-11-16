<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="<?= $clientKey ?>"></script>

<script>
    snap.pay('<?= $snapToken ?>', {
        onSuccess: function(result) {
            // Panggil endpoint untuk ubah status
            fetch('/checkout/paid?order_id=<?= $order->id ?>')
                .then(() => {
                    window.location.href = '/site/index?success=1';
                });
        },
        onPending: function(result) {
            window.location.href = '/site/index?pending=1';
        },
        onError: function(result) {
            window.location.href = '/site/index?error=1';
        }
    });
</script>