<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if(config('services.midtrans.production'))
    <script type="text/javascript" src="https://app.midtrans.com/snap/snap.js"
        data-client-key="{{config('services.midtrans.client_key')}}"></script>
    @else
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{config('services.midtrans.client_key')}}"></script>
    @endif
</head>

<body>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', (event) => {
            snap.pay('{{ $snapToken }}', {
                onSuccess: (result) => {
                    window.location.replace('about:blank');
                },
                onPending: function (result) {
                    window.location.replace('about:blank');
                },
                onError: function (result) {
                    alert('Oops. Something went wrong.');
                    window.location.replace('about:blank');
                },
                onClose: function () {
                    alert('customer closed the popup without finishing the payment');
                    window.location.replace('about:blank');
                }
            });
        });

    </script>
</body>

</html>
