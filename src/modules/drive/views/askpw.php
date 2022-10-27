<?php 
require 'header.php';
?>
<div id="ask-password" class="modal">
    <form method="POST">
        <h2>Introduce la contraseña</h2>
<?php if ($incorrect_pw ?? false) : ?>
         Incorrect password
<?php endif; ?>
        <input name="password" placeholder="Contraseña" type="password">
        <button class="self-center" type="submit">Acceder</button>
    </form>
</div>

<button modal-open="ask-password" hidden></button>
<?php
require 'footer.php';
?>