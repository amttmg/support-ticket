<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js'])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>
<script>
    if ("serviceWorker" in navigator) {
        navigator.serviceWorker.register("/service-worker.js").then(async (registration) => {

            const subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: "{{ env('VAPID_PUBLIC_KEY') }}"
            });

            const json = subscription.toJSON();

            // IMPORTANT FIX
            const contentEncoding =
                (PushManager.supportedContentEncodings && PushManager.supportedContentEncodings[0]) ||
                "aesgcm"; // Default for Chrome & Edge

            await fetch("/save-subscription", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    endpoint: json.endpoint,
                    publicKey: json.keys.p256dh,
                    authToken: json.keys.auth,
                    contentEncoding: contentEncoding
                })
            });
        });
    }
</script>

</html>
