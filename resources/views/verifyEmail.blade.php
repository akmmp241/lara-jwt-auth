<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verification Email</title>
</head>
<body>
    <p>{{ $details['body'] }}</p>
    <a href="{{ $details['url'] }}">{{ $details['url'] }}</a>
</body>
</html>
