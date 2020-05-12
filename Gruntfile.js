module.exports = function (grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        less: {
            gallery: {
                files: {
                    'resources/css/gallery.css': ['resources/css/gallery.less'],
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
    grunt.loadNpmTasks('grunt-contrib-less');


    grunt.registerTask('build', ['less', 'cssmin']);
};
