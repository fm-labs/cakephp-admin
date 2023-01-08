<?php
$dashboardTitle = $this->get('dashboard.title', __d('admin', 'Dashboard'));
$dashboardPanels = $this->get('dashboard.panels', []);

$this->Breadcrumbs->add($dashboardTitle);

$this->assign('title', $dashboardTitle);
?>
<div id="admin-user-dashboard" class="admin dashboard index">
    <div class="row">
        <?php foreach ($dashboardPanels as $panel) : ?>
            <?php $panel = array_merge(['cols' => 12, 'elements' => []], $panel); ?>
            <?php foreach ($panel['elements'] as $element) : ?>
                <?php $element = array_merge(['type' => null, 'path' => null, 'data' => []], $element); ?>
                <div class="col-md-<?= $panel['cols']; ?>">
                    <?php
                    try {
                        if (!$element['type'] || !$element['path']) {
                            throw new \InvalidArgumentException('Dashboard element: Missing type or path');
                        }

                        switch ($element['type']) {
                            case "element":
                                if (!$this->elementExists($element['path'])) {
                                    throw new \Exception("Dashboard element: Path not found: " . $element['path']);
                                }
                                echo $this->element($element['path'], $element['data']);
                                break;

                            case "cell":
                                echo $this->cell($element['path'], $element['data']);
                                break;
                        }
                        //echo '<p>' . $element . '</p>';
                    } catch (\Exception $ex) {
                        if (Cake\Core\Configure::read('debug')) {
                            echo '<div class="alert alert-danger">' . $ex->getMessage() . '</div>';
                        }
                    }
                    ?>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
</div>
