<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biersysteem</title>
</head>
<body>
<link href="css/app.css" rel="stylesheet" type="text/css">
<div class="header">
  <a href="#default" class="logo">Biersysteem</a>
    <div class="header-right">
        <a href="biersysteem/admin" class="logo">Admin</a>
    </div>
</div>

@yield('scripts')

@yield('content')

</body>
</html>