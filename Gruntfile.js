module.exports = function(grunt) {

    // Get command line arguments
    var cssFile = grunt.option('css-file') || 'public/css/main.min.css',
        jsFile = grunt.option('js-file') || 'public/js/main.min.js',
        cssFilesTemplate = {},
        jsFilesTemplate = {},
        currentTimestamp = new Date().getTime();

    cssFilesTemplate[cssFile] = [
        'public/css/animate.css',
        'public/css/icomoon.css',
        'public/css/bootstrap.css',
        'public/css/style.css'
    ];
    jsFilesTemplate[jsFile] = [
        'public/js/jquery.min.js',
        'public/js/jquery.easing.1.3.js',
        'public/js/bootstrap.min.js',
        'public/js/jquery.waypoints.min.js',
        'public/js/main.js'
    ];

    // Project configuration.
    grunt.initConfig({

        // Load configuration
        pkg: grunt.file.readJSON('package.json'),

        // Minify app JS files into one file dropping console object
        uglify: {
            options: {
                compress: {
                    drop_console: true
                }
            },
            main: {
                files: jsFilesTemplate
            }
        },

        // Minify CSS files
        cssmin: {
            main: {
                files: cssFilesTemplate
            }
        },

        processhtml: {
            options: {
                // Task-specific options go here.
            },
            main: {
                files: {
                    'templates/partials/javascripts.html.twig': ['templates/partials/javascripts.html.twig'],
                    'templates/partials/stylesheets.html.twig': ['templates/partials/stylesheets.html.twig']
                }
            }
        },

        'string-replace': {
            main: {
                options: {
                    replacements: [{
                        pattern: /(.js|.css)\?v/ig,
                        replacement: '$1?v=' + currentTimestamp
                    }]
                },
                files : {
                    'templates/partials/javascripts.html.twig': ['templates/partials/javascripts.html.twig'],
                    'templates/partials/stylesheets.html.twig': ['templates/partials/stylesheets.html.twig']
                }
            }
        }

    });

    // Load the plugins
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-string-replace');
    grunt.loadNpmTasks('grunt-processhtml');

    // Default task.
    grunt.registerTask('default', ['uglify', 'cssmin', 'processhtml', 'string-replace']);

};
