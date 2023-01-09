<?php
/**
 * @var \Cake\View\View $this
 * @var array $pluginInfo
 */
$this->Breadcrumbs->add(__('Plugins'), ['action' => 'index']);
$this->Breadcrumbs->add(__d('admin', 'Plugin: {0}', $pluginInfo['name']));

$this->assign('title', $pluginInfo['name']);
?>
<div class="view">

    <?php
    if ($this->get('readme')) {
        if (\Cake\Core\Plugin::isLoaded('Markdown')) {
            $this->loadHelper('Markdown.CommonMark');
            $readmeHtml = $this->CommonMark->convert($this->get('readme'));
            echo $this->Html->div('markdown plugin-readme mb-3', $readmeHtml);
        } else {
            echo $this->Html->tag('textarea', $this->get('readme'));
        }
    }
    ?>

    <?= $this->element('Admin.array_to_tablelist', ['array' => $pluginInfo]); ?>

    <hr />
    <?= $this->Html->link(__('Back to {0}', __('Plugins')), ['action' => 'index']); ?>

    <hr />
    <?php debug($pluginInfo); ?>
</div>