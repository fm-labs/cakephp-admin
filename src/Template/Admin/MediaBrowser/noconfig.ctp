<div class="view">
    <h2>Media</h2>

    <?php if (!$pluginLoaded): ?>
    <div class="warning message">
        <h3>The Media Plugin is not loaded</h3>
        <div class="ui divider"></div>
    </div>
    <?php endif; ?>

    <p>
        The media configuration '<strong><?= $configName; ?></strong>' could not be found.
        <br /><br />
        Please create <strong><?= $configName ?>.php</strong> in your config/ directory
    </p>

    <div class="ui divider"></div>
    <h3>Example configuration:</h3>
    <div class="media-example-config" style="background-color: #e8e8e8; font-family: Courier, monospace; padding: 2em;">
        <pre><?= h($configExample); ?></pre>
    </div>
</div>
