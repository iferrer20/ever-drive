<?php 
$title = 'Usuario ' . $data->name;
require 'header.php';
?>

<div class="profile">

<?php
$result = $data->get_own_drives();
?>
<div class="pfp">
<?php if ($data->has_pfp()): ?>
    <img src="/user/pfp/<?= $data->id; ?>">
<?php else: ?>
    <span class="material-icons-round">account_circle</span>
<?php endif; ?>
</div>
<h2><?= $data->name; ?></h2>

<table>
    <tr>
        <th></th>
        <th>Nombre</th>
        <th>Creado el</th>
    </tr>
<?php while ($drive = $result->fetchArray()): ?>
    <tr onclick="window.location = '/<?= $drive['name']; ?>'">
        <td class="icon"><span class="material-icons-round">cloud</span></td>
        <td class="drive"><?= $drive['name']; ?></td>
        <td class="timedate"><?= $drive['c_at']; ?></td>
    </tr>
<?php endwhile; ?>
</table>
</div>