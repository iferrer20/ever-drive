<?php 
require 'header.php';
?>
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