<?php
use Cake\Utility\Hash;

?>
<?php
if (!is_array($data)) {
    echo "No array";
    return;
} elseif (empty($data)) {
    echo "[ ]";
    return;
}
?>
<table class="ui table striped">
    <thead>
    <tr>
        <th><?php echo __d('backend','Key'); ?></th>
        <th><?php echo __d('backend','Value'); ?></th>
    </tr>
    </thead>
    <?php foreach(Hash::flatten($data) as $k => $v): ?>
        <tr>
            <td><?= h($k); ?></td>
            <td><?= (is_array($v)) ? $this->element('Backend.array_to_table', ['data' => $v]) : h($v); ?></td>
        </tr>
    <?php endforeach; ?>
</table>