<?php 
$title = 'Usuario ' . $data->user->name;
require 'header.php';
?>

<form class="modal hidden" id="modal-del-drive" action="/drive/" method="POST">
    <input type="hidden" name="action" value="deldrive">
    <h2 class="center">¿Eliminar drive <span class="modal-arg-1"></span>?</h2>
    <input type="hidden" name="drivename" class="modal-arg-1">
    <div class="buttons">
        <button type="submit">Eliminar</button>
        <button type="button" class="modal-close no-gradient black">Cancelar</button>
    </div>
</form>

<form class="modal hidden a-start" id="modal-logout" action="/user/logout/">
    <h3>¿Cerrar sessión?</h3>
    <button type="submit" class="self-center">Aceptar</button>
    <button type="button" class="self-center modal-close no-gradient black">Cancelar</button>
</form>


<form class="modal hidden a-start" id="modal-edit-user" action="/user/update/" method="POST" enctype="multipart/form-data">
    <h3>Nombre de usuario</h3>
    <input name="name" value="<?= $data->user->name; ?>" placeholder="Nombre de usuario">
    <h3>Email</h3>
    <input name="email" type="text" value="<?= $data->user->email; ?>" placeholder="Email">
    <h3>Cambiar Contraseña <input onchange="$(this).parent().next().prop('disabled', !$(this).is(':checked'))" type="checkbox"></h3>
    <input name="password" type="password" placeholder="Contraseña" disabled>
    <h3 class="d-flex a-center">
        Avatar
        <span class="material-icons-round pointer" onclick="$(this).next().click()">
        file_upload
        </span>
        <input name="pfp" type="file" accept="image/jpeg, image/png" hidden>
    </h3>
    <!-- <button onclick="document.cookie='';window.location='/'" type="button" class="red">Cerrar sessión</button> -->
    <button type="submit" class="self-center">Aceptar</button>
    <button type="button" class="self-center modal-close no-gradient black">Cancelar</button>
</form>

<form class="modal hidden a-start" id="modal-edit-drive" action="/drive/" method="POST">
    <input type="hidden" name="action" value="update">
    <h3>Nombre del drive</h3>
    <input name="drivename" class="modal-arg-1" type="hidden">
    <input name="name" class="modal-arg-1"  placeholder="Nombre del drive">
    <h3>Cambiar Contraseña <input onchange="$(this).parent().next().prop('disabled', !$(this).is(':checked'))" type="checkbox"></h3>
    <input name="password" type="password" placeholder="Contraseña" disabled>
    <button type="submit" class="self-center">Aceptar</button>
    <button type="button" class="self-center modal-close no-gradient black">Cancelar</button>
</form>

<div class="profile">


<?php if ($data->user->has_pfp()): ?>
    <div class="pfp" style="background-image: url('/user/pfp/<?= $data->user->id; ?>')"></div>
<?php else: ?>
    <span class="pfp material-icons-round">account_circle</span>
<?php endif; ?>

<h2 class="d-flex a-center"><?= $data->user->name; ?> 
<?php if ($data->perms): ?>
    <span modal-open="modal-edit-user" class="material-icons-round pointer">settings</span>
    <span modal-open="modal-logout" class="material-icons-round pointer">logout</span>
<?php endif; ?>
</h2>

<?php if ($data->user->has_drives()): ?>
    <table>
        <tr>
            <th></th>
            <th>Nombre</th>
            <th>Creado el</th>
        </tr>
    <?php
        $result = $data->user->get_own_drives();
        while ($drive = $result->fetchArray()): ?>
        <tr>
            <td><span class="driveicon material-icons-round">cloud</span></td>
            <td class="drive"><?= $drive['name']; ?></td>
            <td class="timedate"><?= $drive['c_at']; ?></td>
            <td class="options">
                <a href="/<?= $drive['name']; ?>"><span class="material-icons-round">arrow_forward</span></a>
            <?php if ($data->perms): ?>
                <span modal-open="modal-del-drive" modal-args="<?= $drive['name']; ?>" class="material-icons-round pointer">delete</span>
                <span modal-open="modal-edit-drive" modal-args="<?= $drive['name'] ?>" class="material-icons-round pointer">edit</span>
            <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
<?php else: ?>
    <?php if ($data->perms): ?>
        <h3>No tienes ningún drive</h3>
    <?php else: ?>
        <h3>Este usuario no tiene drives</h3>
    <?php endif; ?>

<?php endif; ?>
</table>
<button onclick="history.back()">Atrás</button>
</div>
