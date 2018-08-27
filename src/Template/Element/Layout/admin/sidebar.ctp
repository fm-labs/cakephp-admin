<aside class="main-sidebar">
    <section class="sidebar">
        <div class="sidebar-toggle">
            <a href="#" data-sidebar-toggle>
                <i class="fa fa-list"></i>
                <span>Menu</span>
            </a>
        </div>
        <?= $this->fetch('sidebar_items'); ?>
    </section>
    <script>
        $(document).ready(function() {
            $('[data-sidebar-toggle]').click(function(ev) {
                $('body').toggleClass('sidebar-collapsed');
                ev.preventDefault();
                return false;
            });
        })
    </script>
</aside>