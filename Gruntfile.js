module.exports = function (grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        sass: {
            options: {
                implementation: require('sass')
            },
            dev: {
                files: {
                    'resources/css/gallery.css': 'resources/css/gallery.scss'
                }
            }
        },
        cssmin: {
            target: {
                files: {
                    'resources/css/gallery.css': ['resources/css/gallery.css']
                }
            }
        }
    });

    //grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-sass');

    grunt.registerTask('build', ['sass', 'cssmin']);
};
