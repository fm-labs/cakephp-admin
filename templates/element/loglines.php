<?php if (isset($log)) : ?>
    <table class="table table-striped table-sm">
        <?php foreach ((array)$log as $logRow) : ?>
            <?php if (is_array($logRow)) {
                [$level, $msg] = $logRow;
            } else {
                $level = "DEBUG";
                $msg = $logRow;
            } ?>
            <tr>
                <td><?= h($level); ?>&nbsp;</td>
                <td><?= h($msg); ?>&nbsp;</td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>