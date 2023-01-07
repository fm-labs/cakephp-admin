<?php
$date = $date ?? [];
?>
<div class="view">
	<h2><?php echo __d('admin', 'Date & Time'); ?></h2>
	<table class="table">
    <?php foreach($date as $key => $val): ?>
        <tr>
            <td><?php echo $key; ?>&nbsp</td>
            <td><?php echo $val;?>&nbsp</td>
        </tr>
    <?php endforeach;?>
	</table>
</div>
