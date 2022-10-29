<?php 
require 'header.php';
?>
<div class="notfound">Path "<b><?= $GLOBALS['uri'] ?></b>" not found in drive <?= $data->drive->name ?></div>
<?php
require 'footer.php';
?>