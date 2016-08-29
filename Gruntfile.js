/*global module:false*/
module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    // Metadata.
    pkg: grunt.file.readJSON('package.json'),
    banner: '/*! <%= pkg.title || pkg.name %> - v<%= pkg.version %> - ' +
      '<%= grunt.template.today("yyyy-mm-dd") %>\n' +
      '<%= pkg.homepage ? "* " + pkg.homepage + "\\n" : "" %>' +
      '* Copyright (c) <%= grunt.template.today("yyyy") %> <%= pkg.author.name %>;' +
      ' Licensed <%= _.pluck(pkg.licenses, "type").join(", ") %> */\n',
    // Task configuration.
    less: {
      development: {
        options: {
          paths: ['webroot/less', 'webroot/css'],
          banner: '/** <%= pkg.title || pkg.name %> - v<%= pkg.version %> - ' +
          '<%= grunt.template.today("yyyy-mm-dd") %> **/\n'
        },
        files: {
          'webroot/css/master.css': 'webroot/less/master.less',
          'webroot/css/admin.css': 'webroot/less/admin.less',
          'webroot/css/auth.css': 'webroot/less/auth.less',
          'webroot/css/tinymce.css': 'webroot/less/tinymce.less'
        }

      },
      production: {
        options: {
          paths: ['webroot/less', 'webroot/css'],
          compress: true,
          plugins: [
            new (require('less-plugin-autoprefix'))({browsers: ["last 2 versions"]}),
            new (require('less-plugin-clean-css'))({ advanced: true })
          ]
        },
        files: {
          'webroot/css/master.min.css': 'webroot/less/master.less',
          'webroot/css/admin.min.css': 'webroot/less/admin.less',
          'webroot/css/auth.min.css': 'webroot/less/auth.less',
          'webroot/css/tinymce.min.css': 'webroot/less/tinymce.less'
        }
      }
    },
    watch: {
      assets: {
        files: [
          'webroot/less/*.less'
        ],
        tasks: ['less'],
        options: {
          spawn: false
        }
      }
    }
  });

  // These plugins provide necessary tasks.
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-watch');

  // Default task.
  //grunt.registerTask('default', ['jshint', 'qunit', 'concat', 'uglify']);
  grunt.registerTask('default', ['less:development']);

};
