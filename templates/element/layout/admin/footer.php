<?php
/**
 * Default admin layout footer element.
 */
?>
<div class="footer hidden-xs">
    <div class="pull-left">
        <?php
        $now = time();
        $expires = $now + (int)ini_get('session.gc_maxlifetime');
        ?>
        <?= __('Session Ends: {0}', date(DATE_COOKIE, $expires)); ?>
    </div>
    <div class="pull-right">
        <small>&copy; <a href="https://www.flowmotion-labs.com" target="_blank">fm-labs</a></small>
    </div>
    <div class="clearfix"></div>
</div>
