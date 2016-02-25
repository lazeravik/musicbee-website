module.exports = function (grunt) {

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        watch: {
            sass: {
                files: 'styles/scss/**/*.scss',
                tasks: [
                    'sass'
                ]
            }
        },
        uglify: {
            build: {
                src: 'js/*.js',
                dest: 'js/build/main.min.js'
            }
        },
        sass: {
            options: {
                outputStyle: 'expanded',
				sourceMap: true,
            },
            dist: {
                files: {
                    'styles/dist/mb_main.css': 'styles/scss/mb_main.scss'
                }
            }
        },
        // autoprefixer: {
        //     options: {
        //         browsers: ['last 2 versions', 'ie 9', 'ie 10', '> 1%']
        //     },
        //     main: {
        //         expand: true,
        //         flatten: true,
        //         src: 'styles/src/*.css',
        //         dest: 'styles/dist/'
        //     }
        // },
         // browserSync: {
             // default_options: {
                 // bsFiles: {
                     // src: [
                         // "styles/dist/*.css",
                         // "*.php",
                     // ]
                 // },
                 // options: {
                     // watchTask: true,
                     // proxy: "localhost",
                     // online: false,
                 // }
             // }
         // }
    });

    // Load the plugin that provides the "uglify" task.
    //grunt.loadNpmTasks('grunt-contrib-uglify');
    //grunt.loadNpmTasks('grunt-sass');
    //grunt.loadNpmTasks('grunt-autoprefixer');
    //grunt.loadNpmTasks('grunt-contrib-watch');
    //grunt.loadNpmTasks('grunt-browser-sync');

    require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);
    // Default task(s).
    // grunt.registerTask('default', ['browserSync', 'watch']);
    grunt.registerTask('default', ['sass', 'watch']);

};