(function($) {


    if (typeof FooTable === "undefined") {
        console.warn("Backend FooTable: Missing FooTable instance");
    }

    FooTable.Table.prototype.urlCache = {
        //foo: 'bar'
    };

    FooTable.Table.prototype.buildUrl = function(baseUrl) {
        var url = '';
        for (var k in this.urlCache) {
            if (this.urlCache.hasOwnProperty(k)) {
                url = url + k + '=' + this.urlCache[k] + '&';
            }
        }

        if (baseUrl && baseUrl.indexOf('?') === false) {
            url = '?' + url;
        } else {
            url = '&' + url;
        }

        return baseUrl + url;
    };



    if (typeof window.Backend === "undefined") {
        console.warn("Backend FooTable: Missing Backend instance");
    }

    Backend = window.Backend || {};
    Backend.FooTable = Backend.FooTable || {};
    Backend.FooTable.Formatters = Backend.FooTable.Formatters || {};

    Backend.FooTable.Formatters.boolean = function(value, options, rowData){
        if (value === null) return '';
        var clazz = (value === "true") ? "text-success" : "text-danger";
        return '<span class="'+ clazz +'"><i class="fa fa-circle"></i></span>';
    };

    Backend.FooTable.Formatters.link = function(value, options, rowData){
        return (value === null) ? '' : '<a href="#" data-id="' + rowData.id +'">' + value + '</a>';
    };

    Backend.FooTable.Formatters.currency = function(value, options, rowData){
        return (value === null) ? '' : '<span class="currency">' + value + '</span>';
    };

    Backend.FooTable.Formatters.related = function(value, options, rowData){
        if (value === null) return '';
        var val = value.name || value.title || value.display_name || value.id || value;
        return '<span class="related">' + val + '</span>';
    };

    Backend.FooTable.Formatters.media_file = Backend.FooTable.Formatters.mediafile = function(value, options, rowData){
        if (value === null || typeof value !== "object") return '<img width="30" src="" />';

        return  '<a href="' + value.full_url +'" target="_blank" data-original-value="' + value.path +'">' +
            '<img width="30" src="' + value.full_url + '" />' +
            '</a>';
    };

    Backend.FooTable.Formatters.status = function(value, options, rowData){
        if (!value || typeof(value) !== "object") {
            return value;
        }

        var clazz = value.class || 'default';
        var status = value.status || -1;
        var label = value.label || '?';

        return '<span class="label label-'+clazz+'"  data-value="' + status + '">' + label + '</span>';
    };

    Backend.FooTable.Formatters.datetime = function(value, options, rowData){
        if (value === null) return '';

        if (typeof(moment) === "undefined") {
            console.warn("MomentJS not loaded");

            var date = new Date(value);
            if (!date) {
                return value;
            }
            //@todo format me properly
            return (date.getMonth()+1) + '/' + date.getDate() + '/' +  date.getFullYear() + ' ' + date.getHours() + ':' + date.getMinutes();
        }

        return moment.utc(value).format("DD MMM YYYY, HH:mm");
    };

    Backend.FooTable.Formatters.actions = function(value, options, rowData, x){

        if (value === null) return '';

        try {
            var actions = {};

            if (typeof value === "string") {
                actions = JSON.parse(value);
            }
            else if (typeof value === "object") {
                actions = value
            }

            $container = $('<div>', {'class': 'btn-group pull-right'});
            _.each(actions, function(action) {
                var $btn = $('<a>')
                    .addClass('btn btn-default btn-sm')
                    //.attr('data-url', action.url)
                    .attr('href', action.url)
                    .attr('title', action.title);

                var icon;
                if (action.attrs.hasOwnProperty('data-icon')) {
                    icon = action.attrs['data-icon'];
                    delete action.attrs['data-icon'];
                }
                if (icon) {
                    $btn.html($('<i>', {'class': 'fa fa-' + icon}));
                } else {
                    $btn.text(action.title);
                }

                $btn.attr(action.attrs)
                    .appendTo($container);
            });

            return $container;
        } catch (ex) {
            console.error("failed to parse actions data", ex);
        }
    };

})(jQuery);