<?php 
require 'header.php';
?>
<div class="context-menu hidden" id="context-menu-default">
    <div modal-open="modal-create-folder">Crear carpeta</div>
    <div class="submit-file">Submit file</div>
</div>
<div class="context-menu hidden" id="context-menu-folder">
    <div modal-open="modal-del-folder">Eliminar carpeta</div>
    <div modal-open="modal-rename" class="rename">Renombrar</div>
</div>
<div class="context-menu hidden" id="context-menu-file">
    <div modal-open="modal-del-file">Eliminar archivo</div>
    <div class="download-file">Descargar archivo</div>
    <div modal-open="modal-rename">Renombrar</div>
</div>
<div class="hidden">
    <form action="" method="POST" enctype="multipart/form-data" id="submit-form">
        <input type="hidden" name="action" value="submitfile">
        <input name="file[]" type="file" id="input-file" multiple>
    </form>
</div>
<div id="upload-file-card" class="transition-visibility hidden">
    Subir a <?= $data->drivename ?>
    <span class="material-icons-round">
    file_upload
    </span>
</div>
<form class="modal hidden" id="modal-create-folder" action="" method="POST">
    <input type="hidden" name="action" value="newfolder">
    <h2>Crear carpeta</h2>
    <input type="text" name="name" placeholder="Folder name">
    <div class="buttons">
        <button type="submit">Crear carpeta</button>
        <button type="button" class="modal-close no-gradient black">Cancelar</button>
    </div>
</form>
<form class="modal hidden" id="modal-del-folder" action="" method="POST">
    <input type="hidden" name="action" value="del">
    <input type="hidden" name="name" class="input-selected-entry">
    <h2 class="center">¿Eliminar carpeta?</h2>
    <div class="buttons">
        <button type="submit">Eliminar</button>
        <button type="button" class="modal-close no-gradient black">Cancelar</button>
    </div>
</form>
<form class="modal hidden" id="modal-del-file" action="" method="POST">
    <input type="hidden" name="action" value="del">
    <input type="hidden" name="name" class="input-selected-entry">
    <h2 class="center">¿Eliminar archivo?</h2>
    <div class="buttons">
        <button type="submit">Eliminar</button>
        <button type="button" class="modal-close no-gradient black">Cancelar</button>
    </div>
</form>
<form class="modal hidden" id="modal-rename" action="" method="POST">
    <input type="hidden" name="action" value="move">
    <input type="hidden" name="from" class="input-selected-entry">
    <h2 class="center">Renombrar</h2>
    <input type="text" name="to" placeholder="Nombre" class="input-selected-entry">
    <div class="buttons">
        <button type="submit">Renombrar</button>
        <button type="button" class="modal-close no-gradient black">Cancelar</button>
    </div>
</form>
<div class="drive-main">
<!-- Drive main -->
    <div class="path">Path</div>
    <div class="info">
        <div class="author">
            <a class="avatar" href="<?= $data->drive->author ? '/user/profile/' . $data->drive->author->name : '' ?>">
                <span class="material-icons-round">account_circle</span>
            </a>
            <?= $data->drive->author?->name ?? 'Anónimo' ?>
        </div>
        <div class="drive">
            <span class="material-icons-round">cloud</span>
            <?= $data->drivename ?>
        </div> 
    </div>

    <div class="explorer"> 
    <!-- Explorer -->
    <table>
        <tr>
            <th></th>
            <th>Filename</th>               
            <th>Size</th>
        </tr>
    <?php if (substr_count($GLOBALS['uri'], '/') > 0) : ?>
        <tr class="entry folder">
            <td class="icon"><span class="material-icons-round folder">folder</td>
            <td class="name">..</td>
            <td class="size"></td>
        </tr>
    <?php endif; ?>

    <?php foreach (get_folders($data->path) as $folder) {
    ?>
        <tr class="entry folder">
            <td class="icon"><span class="material-icons-round">folder</span></td>
            <td class="name"><?= $folder->name ?></td>
            <td class="size"></td>
        </tr>
    <?php } ?>
<?php foreach (get_files($data->path) as $file) {
?>
        <tr class="entry file">
            <?php if (preg_match('/\.png$|\.jpg$|\.jpeg/', $file->name)): ?>
                <td class="thumbnail"><img src="<?= '/' . uri() . '/' . $file->name; ?>"></td>
            <?php else: ?>
                <td class="icon"><span class="material-icons-round">description</span></td>
            <?php endif; ?>
            <td class="name"><?= $file->name ?></td>
            <td class="size"><?= format_bytes($file->size); ?></td>
        </tr>
<?php } ?>
    </table>
    </div>

<!-- End drive main -->
</div>

<?php 
require 'footer.php';
?>