
/**
 * Contact object
 */
acelaya.contact = {

    init: function() {
        this.initContactForm();
    },

    /**
     * Initializes contact form operations
     */
    initContactForm: function() {
        var $contactForm = $('form#contactForm');
        if ($contactForm.size() === 0) {
            return;
        }

        $contactForm.submit(function(e) {
            $contactForm.find('[type=submit]').button('loading');
        });
    },

    /**
     * Enables the submit button once the recaptcha has been selected
     */
    initRecaptcha: function () {
        var $contactForm = $('form#contactForm');
        if ($contactForm.size() === 0) {
            return;
        }

        $contactForm.find('[type=submit]').removeAttr('disabled');
    }

};