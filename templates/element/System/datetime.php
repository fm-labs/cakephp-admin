<?php
$date = $date ?? [];
?>
<div class="view container">
    <?= $this->Box->create(__d('admin', 'Date & Time'), ['class' => 'box-solid']); ?>
    <table class="table">
        <?php foreach($date as $key => $val): ?>
            <tr>
                <td><?php echo $key; ?>&nbsp</td>
                <td><?php echo $val;?>&nbsp</td>
            </tr>
        <?php endforeach;?>
    </table>
    <?= $this->Box->render(); ?>
</div>
