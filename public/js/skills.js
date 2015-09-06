
/**
 * Skills object
 */
acelaya.skills = {

    init: function() {
        this.initKnobs();
    },

    /**
     * Initializes knobs
     */
    initKnobs: function() {
        var knobs = $('input[type=text].knob');
        if (knobs.size() == 0) return;

        //Generate knobs
        knobs.knob({
            width : 80,
            height : 80,
            min : 0,
            max : 100,
            readOnly : true
        });

        knobs.show();
    }

};