<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mail</title>
</head>

<body>

    <span style="color: red">
        Welcome Mr/Mrs. {{ $user->name }}.
        Your email is {{ $user->email }}
    </span>

</body>

</html>
