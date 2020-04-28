<?php
use Cake\Utility\Hash;

?>
<?php
if (isset($array)) {
    $data = $array;
    unset($array);
}
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
        <th><?php echo __d('admin','Key'); ?></th>
        <th><?php echo __d('admin','Value'); ?></th>
    </tr>
    </thead>
    <?php foreach(Hash::flatten($data) as $k => $v): ?>
        <tr>
            <td><?= h($k); ?></td>
            <td><?= (is_array($v)) ? $this->element('Admin.array_to_tablelist', ['data' => $v]) : h($v); ?></td>
        </tr>
    <?php endforeach; ?>
</table>