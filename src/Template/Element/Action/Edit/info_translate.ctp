<div class="form-info">
    <h4>Translations</h4>
    <?php foreach((array) $this->get('locales') as $_locale => $_localeName): ?>
        <?= $this->Html->link($_localeName, ['action' => 'edit', $this->get('entity')->id, 'locale' => $_locale], ['data-locale' => $_locale]) ?>
    <?php endforeach; ?>
</div>