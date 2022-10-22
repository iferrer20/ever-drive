<?php
require 'header.php';
?>
<img src="/img/logo_horizontal.png" alt="logo">
<div class="login">
    <form action="" method="POST">
        <h1>Registrarse</h1>
        <span><?= $data['error'] ?? '' ?></span>
        <input type="text" name="username" placeholder="Usuario">
        <input type="text" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Contraseña">
        <button>Registrarse</button>
    </form>
</div>