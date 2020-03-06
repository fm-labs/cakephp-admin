<?php
$this->loadHelper('Backend.DataTable');
//$this->loadHelper('Backend.DataTableJs');

if (empty($dataTable['class'])) {
    $dataTable['class'] = 'table table-condensed table-striped table-hover';
}
?>
<?php if (isset($dataTable['title'])) : ?>
<h4><?= h($dataTable['title']); ?></h4>
<?php endif; ?>

<?php
$html = $this->DataTable->create($dataTable, $data)->render();
echo $html;

return;
?>

<!-- DataTable JS
<script type="text/javascript">
    $(document).ready(function() {

        var dtId = '<?= $this->DataTable->id(); ?>';
        var dtTable = '<?= $this->DataTable->getParam('model'); ?>';
        var dtSortUrl = '<?= $this->Html->Url->build($this->DataTable->getParam('sortable')); ?>';
        var $el = $('#' + dtId);

        console.log("loading datatable js for " + dtId);


        //originally from http://stackoverflow.com/questions/1307705/jquery-ui-sortable-with-table-and-tr-width/1372954#1372954
        var fixHelperModified = function(e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function(index)
            {
                $(this).width($originals.eq(index).width())
            });
            return $helper;
        };

        //
        // Jquery UI Sortable DataTable
        //
        if ($el.attr('data-sortable') == 1) {

            console.log("init sortable for dt " + dtId);

            if (!$.fn.sortable) {
                console.warn("JqueryUI sortable not loaded");
            } else {
                console.log("initialize sortable")
                $el.find(".dtable-body").sortable({
                    placeholder: "ui-sortable-placeholder", // "ui-state-highlight",
                    helper: fixHelperModified,
                    update: function(event, ui) {
                        console.log(ui);
                        console.log(event);

                        var sibling = ui.item.prev();
                        var siblingId = 0;
                        if (sibling.length > 0) {
                            siblingId = sibling.data().id;
                        }

                        var updateData = { id: ui.item.data().id, after: siblingId, model: dtTable };
                        console.log(updateData);

                        if (dtTable && dtSortUrl) {
                            $.ajax({
                                type: 'POST',
                                url: dtSortUrl,
                                data: updateData,
                                dataType: 'json',
                                success: function(data, textStatus, xhr) {
                                    //console.log(textStatus);
                                    console.log(data);

                                    if (data.error !== undefined) {
                                        alert("Ups. Something went wrong! " + data.error);
                                        return;
                                    }
                                },
                                error: function(err) {
                                    alert("Ups. Something went wrong. Please try again");
                                    console.error(err);
                                }
                            });
                        }


                    }
                });
                //.disableSelection();
            }

        } else {
            console.log("Datatable " + dtId + " is not sortable");
        }

        //$el.dataTable();

    });
</script>
 -->
