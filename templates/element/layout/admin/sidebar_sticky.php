<div class="position-sticky pt-3 sidebar-sticky">
    <?= $this->fetch('sidebar_content') ?>

    <div id="sidebar-close" class="p-3">
        <a href="#" id="sidebar-close-btn">Close</a>
    </div>
    <?php $this->append('script'); ?>
    <script>
        $('#sidebar-close-btn').click((e) => {
            console.log("clicked");
            $('#sidebarMenu').hide().remove();
            e.stopPropagation();
            e.preventDefault();
        })
    </script>
    <?php $this->end(); ?>
</div>
