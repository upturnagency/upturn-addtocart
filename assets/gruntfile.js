module.exports = function(grunt) {

    var PathConfig = require('./grunt-settings.js');
    require('jit-grunt')(grunt);

    grunt.initConfig({

        config: PathConfig,

        /**
         * Compiling stylesheets
         */

        // Import whole folder into a file
        sass_globbing: {
            target: {
                files: {
                    '<%= config.scssDir %>_parts.scss': '<%= config.scssPartials %>/*.scss',
                }
            }
        },

        //sass
        sass: {
            min: {
                options: {
                    style: 'expanded'
                },
                files: [
                    {
                        expand: true,
                        cwd: '<%= config.scssDir %>',
                        src: ['**/*.scss', '!<%= config.scssMainFileName %>.scss'],
                        dest: '<%= config.cssDir %>',
                        ext: '.css'
                    },
                    {
                        src: '<%= config.scssDir %><%= config.scssMainFileName %>.scss',
                        dest: '<%= config.cssDir %><%= config.cssMainFileName %>.css'
                    }
                ]
            }
        },

        // Autoprefix
        postcss: {
            options: {
                map: false,
                processors: [
                    require('autoprefixer')({
                        browsers: ['> 20%', 'last 10 versions', 'Firefox > 20']
                    })
                ],
                remove: false
            },
            dist: {
                src: '<%= config.cssDir %>*.css'
            }
        },

        /**
         * Minifications
         */

        // Refactor CSS incase anything duplicates
        csso: {
            restructure: {
                options: {
                    restructure: true,
                    report: 'min'
                },
                files: {
                    '<%= config.cssDir %><%= config.cssMainFileName %>.min.css': ['<%= config.cssDir %><%= config.cssMainFileName %>.min.css']
                }
            },
            compress: {
                options: {
                    report: 'gzip'
                },
                files: {
                    '<%= config.cssDir %><%= config.cssMainFileName %>.min.css': ['<%= config.cssDir %><%= config.cssMainFileName %>.min.css']
                }
            },
            dynamic_mappings: {
                expand: true,
                cwd: 'assets/css/',
                src: ['*.css', '!*.min.css'],
                dest: 'assets/css/',
                ext: '.min.css'
            }
        },

        // IE9/8 stylesheets
        stripmq: {
            //Viewport options
            options: {
                width: 1600,
                type: 'screen'
            },
            all: {
                files: {
                    '<%= config.cssDir %>ie.css': ['<%= config.cssDir %><%= config.cssMainFileName %>.min.css']
                }
            }
        },

        // Watch for any changes
        watch: {
            css: {
                // Watch sass changes, merge mqs & run bs
                files: ['<%= config.scssDir %>*.scss', '<%= config.scssDir %>**/*.scss'],
                tasks: ['sass_globbing:target',  'sass', 'postcss:dist', 'csso' ]
            },
        }
    });

    // Standard grunt task – compile css and watch
    grunt.registerTask('default', [
        'sass_globbing:target', // Glob together needed folders
        'sass', // Run sass
        'postcss:dist', // Post Process with Auto-Prefix
        'csso', // Run sass
        'watch' // Keep watching for any changes
    ]);

};