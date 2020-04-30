<div>
    <h1>System Health</h1>
    <table class="table">
    <?php foreach ($this->get('health') as $check => $results) : ?>
        <?php foreach ($results as $result) : ?>
    <tr>
        <td><?= h($check) ?></td>
        <td><?= h($result[0]) ?></td>
        <td><?= h((int)$result[1]) ?></td>
    </tr>
        <?php endforeach; ?>
    <tr>
        <td>---</td>
    </tr>
    <?php endforeach; ?>
    </table>
</div>
