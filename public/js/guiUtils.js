/**
 * GuiUtils object
 */
acelaya.guiUtils = {

    init : function() {
        this.initTooltips();
        this.initCarouselAnimation();
    },

    initTooltips : function() {
        $("[data-toggle=tooltip]").tooltip();
    },

    initCarouselAnimation : function() {
        $(".carousel").carousel();
    }

};