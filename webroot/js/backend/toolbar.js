
/**
 * Backend Toolbar
 * Register to Backend.Renderer event listener
 *
 * @link http://getbootstrap.com/javascript/#affix
 * @link http://stackoverflow.com/questions/22899844/unaffix-event-for-bootstrap-affix
 * affix.bs.affix => before fixed positioning is applied to an element
 * affixed.bs.affix => after fixed positioning is applied to an element
 * affix-top.bs.affix => before a top element returns to its original (non-fixed) position
 * affixed-top.bs.affix => after a top element returns to its original (non-fixed) position
 * affix-bottom.bs.affix => before a bottom element returns to its original (non-fixed) position
 * affixed-bottom.bs.affix => after a bottom element returns to its original (non-fixed) position
 */
Backend.Renderer.addListener('docready', function(scope) {

    $(scope).find('.toolbar-affix').each(function() {
        $(this).affix({
            offset: {
                top: $(this).data('offsetTop') || undefined
            },
            target: $(scope)
        });
    });

    // affix.bs.affix => before fixed positioning is applied to an element
    $(document).on('affix.bs.affix', $(scope), function(ev) {
        console.log("Affix fixed at " + $(ev.target).offset().top);
        $(ev.target).addClass('navbar-fixed-top navbar-inverse');
    });

    // affix-top.bs.affix => before a top element returns to its original (non-fixed) position
    $(document).on('affixed-top.bs.affix', $(scope), function(ev) {
        console.log("Affix non-fixed at " + $(ev.target).offset().top);
        $(ev.target).removeClass('navbar-fixed-top navbar-inverse');
    })

});
