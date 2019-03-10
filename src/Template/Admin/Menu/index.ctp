<?php
$this->loadHelper('Bootstrap.Menu');

echo $this->Menu->create([
    //'templates' => $menuTemplates,
    //'classes' => $menuClasses,
    'items' => $menu->getItems()
])
    ->render();