<?php 
require 'header.php';
?>
<div class="hidden" id="context-menu">
    <div class="create-directory">Create directory</div>
    <div class="submit-file">Submit file</div>
</div>
<div class="hidden">
    <form action="" method="POST" enctype="multipart/form-data" id="submit-form">
        <input type="hidden" name="action" value="submitfile">
        <input name="file" multiple="multiple" type="file" id="input-file">
    </form>
</div>
<div class="modal" id="modal-create-folder">
    <form action="" method="POST">
        <input type="hidden" name="action" value="newfolder">
        <h2>Create folder</h2>
        <input type="text" name="name" placeholder="Folder name">
        <button class="modal-close self-center">Create folder</button>
    </form>
</div>
<button class="hidden" modal-open="modal-create-folder">Crear drive ahora</button>
<div class="drive-main">
<!-- Drive main -->
    <div class="path">Path</div>
    <div class="info">
        <div class="author">
            <span class="material-icons-round">account_circle</span>
            Anonymous
        </div>
        <div class="drive">
            <span class="material-icons-round">cloud</span>
            <?= $this->drivename ?>
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
        <tr class="entry">
            <td class="icon"><span class="material-icons-round folder">folder</td>
            <td class="name">..</td>
            <td class="size"></td>
        </tr>
<?php endif; ?>

<?php foreach ($files as $file) {
?>
        <tr class="entry">
            <td class="icon"><span class="material-icons-round <?= $file->is_directory ? "folder\">folder" : "file\">description" ?></span></td>
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