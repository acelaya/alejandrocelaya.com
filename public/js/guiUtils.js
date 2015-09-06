/**
 * GuiUtils object
 */
acelaya.guiUtils = {

    init: function() {
        this.initTooltips();
    },

    initTooltips: function() {
        $('[data-toggle=tooltip]').tooltip();
    }

};