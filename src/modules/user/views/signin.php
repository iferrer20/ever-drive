<?php
$title = 'Acceso';
require 'header.php';
?>
<img src="/img/logo_horizontal.png" alt="logo">
<div class="login">
    <form action="" method="POST">
        <h1>Iniciar sessión</h1>
        <span><?= $data['error'] ?? '' ?></span>
        <input type="text" name="username" placeholder="Usuario">
        <input type="password" name="password" placeholder="Contraseña">
        <button type="submit">Acceder</button>
        <a href="signup"><button type="button" class="no-gradient black small">Registrarse</button></a>
    </form>
</div>