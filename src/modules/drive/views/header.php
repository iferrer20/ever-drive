<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet"> 
    <!-- icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Round">

    <!-- styles -->
    <link rel="stylesheet" href="/css/styles.css">

    <!-- scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Ever drive - <?= $data->drive->name ?></title>
</head>
<body>
<div class="header">           
    <img src="/img/logo_horizontal.png" alt="logo">
    <input type="text" placeholder="Buscar en drive">
    <a class="avatar" href="/user/myprofile/">
    <?php if ($data->user?->has_pfp()): ?>
        <span class="pfp" style="background-image: url('/user/pfp/<?= $data->user->id; ?>')">
        </span>
    <?php else: ?>
        <span class="material-icons-round">
        account_circle
        </span>
    <?php endif; ?>
    </a>
</div>
<div draggable="false" class="shadow hidden transition-visibility"></div>
