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
<?php $this->append('script'); ?>
<script>
    $(document).ready(function() {
        if (window.AdminJs) {
            var adminBaseUrl = window.AdminJs.settings.adminUrl;
            var url = adminBaseUrl + '/system/auth/session';

            setInterval(function() {
                $.ajax(url, {
                    success: function(data) {
                       console.log("ping-success", data);
                    },
                    complete: function(data) {
                        console.log("ping-complete", data);
                    },
                    error: function(data) {
                        console.log("ping-error", data);
                    },
                });
            }, 60000);
        }
    });
</script>
<?php $this->end(); ?>
