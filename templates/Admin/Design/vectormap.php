<?php $this->extend('base'); ?>
<?php $this->loadHelper('Sugar.VectorMap'); ?>

<!-- SECTION Vector maps -->
<div class="section-header">
    Vector Map (jqvmap)
</div>
<div id="vmap" style="width: 600px; height: 400px;"></div>
<?php $this->append('script'); ?>
<script type="text/javascript" src="/admin/libs/jqvmap/maps/jquery.vmap.world.js" charset="utf-8"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        //jQuery('#vmap').vectorMap({ map: 'world_en' });

        jQuery('#vmap').vectorMap(
            {
                map: 'world_en',
                backgroundColor: '#a5bfdd',
                borderColor: '#818181',
                borderOpacity: 0.25,
                borderWidth: 1,
                color: '#f4f3f0',
                enableZoom: true,
                hoverColor: '#c9dfaf',
                hoverOpacity: null,
                normalizeFunction: 'linear',
                scaleColors: ['#b6d6ff', '#005ace'],
                selectedColor: '#c9dfaf',
                selectedRegions: null,
                showTooltip: true,
                onRegionClick: function(element, code, region)
                {
                    var message = 'You clicked "'
                        + region
                        + '" which has the code: '
                        + code.toUpperCase();

                    alert(message);
                }
            });

    });

</script>
<?php $this->end(); ?>

