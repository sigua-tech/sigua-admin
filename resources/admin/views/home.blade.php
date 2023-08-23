<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>丝瓜管理后台</title>
    <link id="favicon" rel="shortcut icon"
          href="data:image/png;base64,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"
          type="image/png"/>
    <script>
        window.userInfo = @json($userInfo) || {}; // 当前登录用户信息绑定到全局。
    </script>
    @vite(['resources/admin/app.js'])
</head>
<body>
<div id="app" class="app"></div>
</body>
</html>