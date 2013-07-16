module.exports = (grunt) ->

  grunt.initConfig
    coffee:
      compile:
        expand: true
        flatten: true
        cwd: 'app/webroot/js'
        src: ['*.coffee']
        dest: 'app/webroot/js/'
        ext: '.js'
    less:
      compile:
        options:
          paths: ["app/webroot/less"]
        files:
          "app/webroot/css/style.css": "app/webroot/css/style.less"

    watch:
      coffee_dev:
        files: 'app/webroot/js/**/*.coffee'
        tasks: ['coffee:compile']
      less_dev:
        files: 'app/webroot/css/*.less'
        tasks: ['less:compile']

  grunt.loadNpmTasks 'grunt-contrib-coffee'
  grunt.loadNpmTasks 'grunt-contrib-watch'
  grunt.loadNpmTasks 'grunt-contrib-less'

  grunt.registerTask('default', ['less:compile', 'coffee:compile'])