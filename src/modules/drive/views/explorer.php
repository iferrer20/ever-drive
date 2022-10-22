<?php 
require 'header.php';
?>
<div class="context-menu hidden" id="context-menu-default">
    <div class="create-folder">Create folder</div>
    <div class="submit-file">Submit file</div>
</div>
<div class="context-menu hidden" id="context-menu-folder">
    <div class="del-folder">Delete folder</div>
</div>
<div class="context-menu hidden" id="context-menu-file">
    <div class="del-file">Delete file</div>
</div>
<div class="hidden">
    <form action="" method="POST" enctype="multipart/form-data" id="submit-form">
        <input type="hidden" name="action" value="submitfile">
        <input name="file[]" type="file" id="input-file" multiple>
    </form>
    <form action="" method="POST" id="move-form">
        <input type="hidden" name="action" value="move">
        <input type="hidden" name="from" class="input-selected-entry">
        <input type="hidden" name="to" class="input-destination">
        <button type="submit" id="move-entry"></button>
    </form>
</div>
<div id="upload-file-card" class="transition-visibility hidden">
    Subir a <?= $data->drivename ?>
    <span class="material-icons-round">
    file_upload
    </span>
</div>
<div class="modal hidden" id="modal-create-folder">
    <form action="" method="POST">
        <input type="hidden" name="action" value="newfolder">
        <h2>Create folder</h2>
        <input type="text" name="name" placeholder="Folder name">
        <button class="modal-close self-center" type="submit">Crear carpeta</button>
    </form>
</div>
<div class="modal hidden" id="modal-del-folder">
    <form action="" method="POST">
        <input type="hidden" name="action" value="delfolder">
        <input type="hidden" name="name" class="input-selected-entry">
        <h2 class="center">¿Eliminar?</h2>
        <div class="buttons">
            <button type="submit">Eliminar</button>
            <button type="button" class="modal-close no-gradient black">Cancelar</button>
        </div>
    </form>
</div>
<div class="modal hidden" id="modal-del-file">
    <form action="" method="POST">
        <input type="hidden" name="action" value="delfile">
        <input type="hidden" name="name" class="input-selected-entry">
        <h2 class="center">¿Eliminar?</h2>
        <div class="buttons">
            <button type="submit">Eliminar</button>
            <button type="button" class="modal-close no-gradient black">Cancelar</button>
        </div>
    </form>
</div>
<div class="drive-main">
<!-- Drive main -->
    <div class="path">Path</div>
    <div class="info">
        <div class="author">
            <a href="<?= $data->drive->author ? '/user/profile/' . $data->drive->author->name : '' ?>">
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
            <td class="icon"><span class="material-icons-round">description</span></td>
            <td class="name"><?= $file->name ?></td>
            <td class="size"><?= $file->size ?></td>
        </tr>
<?php } ?>
    </table>
    </div>

<!-- End drive main -->
</div>

<?php 
require 'footer.php';
?>