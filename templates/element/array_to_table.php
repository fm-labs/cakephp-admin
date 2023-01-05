<?php

$array = (isset($array)) ? $array : [];
$headers = (isset($headers)) ? $headers : [];

if (empty($headers)) {
    if (isset($array[0])) {
        foreach (array_keys($array[0]) as $h) {
            $headers[$h] = ['title' => $h];
        }
    }
}

?>
<table class="table table-sm">
    <thead>
    <tr>
    <?php foreach ($headers as $h => $hconf): ?>
    <th data-key="<?= $h ?>"><?= h($hconf['title']); ?></th>
    <?php endforeach; ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($array as $row): ?>
        <tr>
        <?php foreach (array_keys($headers) as $h): ?>
            <td data-key="<?= $h ?>">
                <?php if (is_array($row[$h])) {
                    //echo $this->element('array_list', ['array' => $row[$h], 'collapse' => true]);
                    echo '(array)';
                } else {
                    echo h($row[$h]);
                }
                ?>
            </td>
        <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>