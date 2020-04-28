<?php
$element = ($element) ?: 'Admin.TreeView/list';
?>
<style>
    .treeview {
        display: inline-flex;
        min-width: 330px;
    }

    .treeview ul {
        list-style-position: outside;
        list-style: none;
        margin: 0;
        padding: 0;

        -webkit-transition: -webkit-transform .3s ease-in-out,height .3s ease-in-out;
        -moz-transition: -moz-transform .3s ease-in-out,height .3s ease-in-out;
        -o-transition: -o-transform .3s ease-in-out,height .3s ease-in-out;
        transition: transform .3s ease-in-out,height .3s ease-in-out;
    }

    .treeview ul li .item {
        margin-bottom: 3px;
        border: 1px solid green;
    }

    .treeview ul li a,
    .treeview ul li span {
        padding: 5px 5px 5px 10px;
        display: inline-block;
        font-size: 14px;
    }

    .treeview ul li a {
        width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .treeview ul li span.actions {
        display: none;
    }

    .treeview ul li > .item:hover span.actions {
    }

    /* Sub lists */
    .treeview ul li ul {
        margin-left: 35px;
    }

    /* Collapsed */
    .treeview ul.collapsed {
        height: 0;
        opacity: 0;
        visibility: hidden;
    }

    /** Handle */
    .treeview .handle {
        width: 28px;
        border-color: transparent;
    }

    .treeview .handle::before {
        display: inline-block;
        margin: 0 .25rem 0 0;
        width: 1.18em;
        height: 1em;
        font-family: 'FontAwesome';
        font-style: normal;
        font-weight: 400;
        text-decoration: inherit;
        text-align: center;
        font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        -webkit-font-smoothing: antialiased;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        content: "\f10c";
    }
    .treeview ul li.has-children > .item > .handle::before {
        content: "\f054";
    }

    .treeview ul li.has-children.collapsed > .item > .handle::before {
        content: "\f078";
    }
</style>
<div class="treeview">
    <?php echo $this->element($element, compact('items', 'element')); ?>
</div>
<script>
    $(document).ready(function() {

        $('.treeview ul li').each(function() {
            if ($(this).find('ul').length > 0) {
                $(this).addClass('has-children');
                $(this).children('ul').addClass('collapsed');
            }
        });

       $('.treeview ul li .handle').on('click', function(ev) {
           console.log($(this));
           $(this).closest('li').children('ul').toggleClass('collapsed');
           $(this).closest('li').toggleClass('collapsed');
           ev.preventDefault();
           ev.stopPropagation();
           return false;
       });
    });
</script>