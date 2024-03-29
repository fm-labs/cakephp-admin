<?php
return;
$searchUrl = Cake\Core\Configure::read('Admin.Search.searchUrl');
if (!$searchUrl) {
    //echo 'Admin.Search: Search Url not defined';
    return;
}
?>
<li>
<form class="navbar-form navbar-left" id="header-search-form" method="get" action="<?= $this->Html->Url->build(); ?>">
    <div class="form-group">
        <input type="text" class="form-control" name="q" placeholder="Search">
    </div>
    <button type="submit" class="btn btn-outline-secondary">Submit</button>
</form>
</li>
<script>
    $(document).ready(function() {
        var $form = $('#header-search-form');
        $form.on('submit', function(ev) {

            var q = $(this).find('input[name=q]').val();
            var searchUrl = $(this).attr('action');
            var queryUrl = searchUrl + '/?q=' + q;

            console.log("[search] Submit query " + q + " to " + queryUrl);

            $.getJson(queryUrl, function(data) {
                console.log("[search] Results", data);
            });

            ev.preventDefault();
            return false;
        });
    });
</script>