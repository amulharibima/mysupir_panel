<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="{{ mix('/js/app.js') }}"></script>
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    </head>
    <body>
    <button id="con">konek id {{ Auth::id()}}</button>
    <script>
    const btn = $('#con');

    btn.click(function() {

        Echo.private('App.User.8').notification((notification) => {
            console.log(notification );
        });
    })
        // var channel = Echo.channel('posts');
        // channel.listen('.room-test', function(data) {
        //     alert(JSON.stringify(data));
        // });
    </script>


    </body>
</html>