<div class="<?= $config['toolbar_class'] ?>">
    <div class="ui opaque menu navbar grid">
        <?php
        foreach ($items as $item) {
            echo $this->element($config['item_element'], ['item' => $item, 'config' => $config, 'options' => $options]);
        }
        ?>
    </div>
</div>


