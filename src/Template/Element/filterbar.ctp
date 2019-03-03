<?php
/**
 * Filterbar element
 *
 * Creates a form from given filters
 */
$filters = (isset($filters)) ? $filters : null;
?>
<style>
    .tab-pane .filter-form-container {
        display: none;
    }

    .filter-form-container {
        margin: 0.5em 0;
    }

    .filter-form-container button {
        vertical-align: top;
    }

    .filter-form-input {
        display: inline-block;
        width: 220px;
        max-width: 220px;
    }
</style>
<?php if ($filters) : ?>
    <div class="filter-form-container" style="margin: 0.5em 0">
        <?= $this->Form->create(null, ['method' => 'GET', 'url' => ['action' => 'index'], 'templates' => [
            'inputContainer' => '<div class="filter-form-input">{{content}}</div>',
            'formGroup' => '<div>{{label}}{{help}}{{input}}{{error}}</div>',
            'label' => ''
        ]]); ?>
        <?= $this->Form->hidden('_form', ['value' => 'filter']); ?>
        <?php foreach ($this->get('filters') as $filter => $inputOptions) : ?>
            <?php $inputOptions['value'] = $this->request->query('_filter.' . $filter); ?>
            <?= $this->Form->input('_filter.' . $filter, $inputOptions); ?>
        <?php endforeach; ?>
        <?= $this->Form->button('<i class="fa fa-search"></i>', ['escape' => false, 'class' => 'btn btn-primary']); ?>
        <?= $this->Form->end(); ?>
    </div>
<?php endif; ?>