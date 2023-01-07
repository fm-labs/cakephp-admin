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
    clean: {
      build: ['webroot/libs/']
    },

    less: {
      development: {
        options: {
          paths: ['webroot/less', 'webroot/css'],
          banner: '/** <%= pkg.title || pkg.name %> - v<%= pkg.version %> **/\n'
        },
        files: {
          'webroot/css/layout/layout.auth.css': 'webroot/less/layout/layout.auth.less',
          'webroot/css/layout/layout.admin.css': 'webroot/less/layout/layout.admin.less',
          'webroot/css/layout/layout.iframe.css': 'webroot/less/layout/layout.iframe.less',
          'webroot/css/layout/dark.css': 'webroot/less/layout/dark.less',
          'webroot/css/admin/admin.css': 'webroot/less/admin/admin.less',
          'webroot/css/jstree/themes/admin/style.css': 'webroot/less/jstree/themes/admin/style.less'
        }

      },
      production: {
        options: {
          paths: ['webroot/less', 'webroot/css'],
          compress: true,
          plugins: [
            //new (require('less-plugin-autoprefix'))({browsers: ["last 2 versions"]}),
            //new (require('less-plugin-clean-css'))({ advanced: true })
          ]
        },
        files: {
          'webroot/css/layout/layout.auth.min.css': 'webroot/less/layout/layout.auth.less',
          'webroot/css/layout/layout.admin.min.css': 'webroot/less/layout/layout.admin.less',
          'webroot/css/layout/layout.iframe.min.css': 'webroot/less/layout/layout.iframe.less',
          'webroot/css/layout/dark.min.css': 'webroot/less/layout/dark.less',
          'webroot/css/admin/admin.min.css': 'webroot/less/admin/admin.less',
          'webroot/css/jstree/themes/admin/style.min.css': 'webroot/less/jstree/themes/admin/style.less'
        }
      }
    },
    copy: {
      libs: {
        options: {
          processContentExclude: ['**/*.{png,gif,jpg,ico,psd,ttf,otf,woff,svg}'],
          noProcess: ['**/*.{png,gif,jpg,ico,psd,ttf,otf,woff,woff2,svg}'], // processContentExclude has been renamed to noProcess
          encoding: null
        },
        files: [
          // includes files within path
          //{expand: true, cwd: 'node_modules/bootstrap/', src: ['dist/**'], dest: 'webroot/libs/bootstrap/'},
        ]
      },
    },
    watch: {
      assets: {
        files: [
          'webroot/less/**/*.less'
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
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-clean');

  // Default task.
  grunt.registerTask('default', ['less']);
  grunt.registerTask('build', ['clean', 'less', 'copy']);

};
