var acelaya = {};

/**
 * Startup operations
 */
$(document).ready(function() {
    acelaya.contact.init();
});

function recaptchaCallaback (value) {
    acelaya.contact.initRecaptcha(value);
}
