<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel-jwt </title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>

<script>
    let token = localStorage.getItem('user_token');

    if (window.location.pathname === '/login' || window.location.pathname === '/register') {
        if (token) {
            window.open('/profile', '_self');
        }
    } else {
        if (!token) {
            window.open('/login', '_self');
        }
    }
</script>
