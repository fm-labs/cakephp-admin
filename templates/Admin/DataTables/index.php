<?php
use Cake\Routing\Router;

$modelName = $this->request->getQuery('model');
$id = $this->request->getQuery('id');
$url = Router::url(['action' => 'ajax', 'model' => $modelName]);
?>
<html>
<head>
    <title>DataTables</title>

    <link rel="stylesheet" type="text/css" href="/boneform-js/node_modules/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/boneform-js/node_modules/font-awesome/css/font-awesome.min.css" />

    <script src="/boneform-js/node_modules/jquery/dist/jquery.js"></script>
    <script src="/boneform-js/node_modules/underscore/underscore.js"></script>
    <script src="/boneform-js/node_modules/backbone/backbone.js"></script>
    <script src="/boneform-js/node_modules/bootstrap/dist/js/bootstrap.js"></script>

    <?= $this->Html->css('Admin./js/datatables/dataTables.bootstrap.css'); ?>
    <?= $this->Html->script('Admin.datatables/jquery.dataTables'); ?>
    <?= $this->Html->script('Admin.datatables/dataTables.bootstrap'); ?>

    <style>
        html {
            overflow-y: scroll;
        }
    </style>
</head>
<body>

<div id="dt-container" class="container">
    <h1>DataTables with CakePHP</h1>


    <table id="test-table" class="table">
        <thead>

        </thead>
        <tbody>

        </tbody>
        <tfoot>

        </tfoot>
    </table>

</div> <!-- #dt-container -->

<script>
$('#test-table').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": "<?= $url ?>",
    "columns": [
        { "data": "id", title: "Id" },
        { "data": "nr_formatted", title: "Nr Formatted" }
    ]
});
</script>

</body>
</html>