/**
 * @deprecated
 */
$(document).ready(function() {

    var storage;
    if (!!window.localStorage) {
        //console.log("Supports localStorage");
        storage = localStorage;
    } else if (!!window.sessionStorage) {
        //console.log("Supports sessionStorage")
        storage = sessionStorage;
    } else {
        // @TODO fallback with cookie storage
    }

    $('#be-sidebar-toggle').click(function(e) {
        //console.log(e);

        var $sb = $('#be-sidebar');
        $('body').toggleClass('be-sidebar-small');
        $('#be-sidebar-toggle i.icon').toggleClass('left right');

        if (storage !== "undefined") {
            //console.log('Current state: ' + storage.getItem('beSidebarCollapsed'));
            var state = ($('body').hasClass('be-sidebar-small') === true) ? 'true' : 'false';

            //console.log("Updating sidebar state: " + state);
            storage.setItem('beSidebarCollapsed', state);
        }
    });

    //console.log('Current state: ' + storage.getItem('beSidebarCollapsed'));
    if (storage !== "undefined" && storage.getItem('beSidebarCollapsed') === 'true') {
        //console.log("sidebar should be collapsed");
        $('body').addClass('be-sidebar-small');
        $('#be-sidebar-toggle i.icon').toggleClass('left right');
    }
});