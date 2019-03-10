<?php
use Cake\Routing\Router;

$modelName = $this->request->query('model');
$id = $this->request->query('id');
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

    <?= $this->Html->css('Backend./js/datatables/dataTables.bootstrap.css'); ?>
    <?= $this->Html->script('Backend.datatables/jquery.dataTables'); ?>
    <?= $this->Html->script('Backend.datatables/dataTables.bootstrap'); ?>

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