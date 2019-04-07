module.exports = grunt => {

    const cssFile = grunt.option('css-file') || 'public/css/main.min.css';
    const jsFile = grunt.option('js-file') || 'public/js/main.min.js';
    const cssFilesTemplate = {};
    const jsFilesTemplate = {};
    const currentTimestamp = new Date().getTime();

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

    grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),

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

        cssmin: {
            main: {
                files: cssFilesTemplate
            }
        },

        processhtml: {
            main: {
                files: {
                    'templates/partials/javascripts.phtml': ['templates/partials/javascripts.phtml'],
                    'templates/partials/stylesheets.phtml': ['templates/partials/stylesheets.phtml']
                }
            }
        },

        'string-replace': {
            main: {
                options: {
                    replacements: [{
                        pattern: /(.js|.css)\?v/ig,
                        replacement: `$1?v=${currentTimestamp}`
                    }]
                },
                files : {
                    'templates/partials/javascripts.phtml': ['templates/partials/javascripts.phtml'],
                    'templates/partials/stylesheets.phtml': ['templates/partials/stylesheets.phtml']
                }
            }
        },

        imagemin: {
            main: {
                files: [{
                    expand: true,
                    cwd: 'public/img',
                    src: ['**/*.{png,jpg,gif}'],
                    dest: 'public/img'
                }]
            }
        }

    });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-string-replace');
    grunt.loadNpmTasks('grunt-processhtml');
    grunt.loadNpmTasks('grunt-contrib-imagemin');

    grunt.registerTask('default', ['uglify', 'cssmin', 'processhtml', 'string-replace' /*, 'imagemin' */]);

};
