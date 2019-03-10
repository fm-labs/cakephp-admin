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
          banner: '/** <%= pkg.title || pkg.name %> - v<%= pkg.version %> **/\n'
        },
        files: {
          'webroot/css/layout/auth.css': 'webroot/less/layout/auth.less',
          'webroot/css/layout/admin.css': 'webroot/less/layout/admin.less',
          'webroot/css/layout/iframe.css': 'webroot/less/layout/iframe.less',
          'webroot/css/layout/dark.css': 'webroot/less/layout/dark.less',
          'webroot/css/backend.css': 'webroot/less/backend.less',
          'webroot/css/jstree/themes/backend/style.css': 'webroot/less/plugins/jstree/jstree.less'
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
          'webroot/css/layout/auth.min.css': 'webroot/less/layout/auth.less',
          'webroot/css/layout/admin.min.css': 'webroot/less/layout/admin.less',
          'webroot/css/layout/iframe.min.css': 'webroot/less/layout/iframe.less',
          'webroot/css/layout/dark.min.css': 'webroot/less/layout/dark.less',
          'webroot/css/backend.min.css': 'webroot/less/backend.less',
          'webroot/css/jstree/themes/backend/style.min.css': 'webroot/less/plugins/jstree/jstree.less'
        }
      }
    },
    copy: {
      bower: {
        options: {
          processContentExclude: ['**/*.{png,gif,jpg,ico,psd,ttf,otf,woff,svg}'],
          noProcess: ['**/*.{png,gif,jpg,ico,psd,ttf,otf,woff,woff2,svg}'], // processContentExclude has been renamed to noProcess
          encoding: null
        },
        files: [
          // includes files within path
          //{expand: true, cwd: 'bower_components/AdminLTE/', src: ['dist/**', 'bootstrap/**'], dest: 'webroot/libs/adminlte/'},
          {expand: true, cwd: 'bower_components/backbone/', src: ['**'], dest: 'webroot/libs/backbone/'},
          {expand: true, cwd: 'bower_components/bootstrap/', src: ['dist/**'], dest: 'webroot/libs/bootstrap/'},
          {expand: true, cwd: 'bower_components/chosen/', src: ['*.js', '*.css', '*.png'], dest: 'webroot/libs/chosen/'},
          {expand: true, cwd: 'bower_components/font-awesome/', src: ['css/**', 'fonts/**'], dest: 'webroot/libs/fontawesome/'},
          {expand: true, cwd: 'bower_components/html5shiv/dist/', src: ['**'], dest: 'webroot/libs/html5shiv/'},
          {expand: true, cwd: 'bower_components/ionicons/', src: ['css/**', 'fonts/**', 'png/**'], dest: 'webroot/libs/ionicons/'},
          {expand: true, cwd: 'bower_components/image-picker/image-picker/', src: ['**'], dest: 'webroot/libs/image-picker/'},
          {expand: true, cwd: 'bower_components/jquery/dist/', src: ['**'], dest: 'webroot/libs/jquery/'},
          {expand: true, cwd: 'bower_components/jquery-ui/', src: ['*.js', 'themes/base/**'], dest: 'webroot/libs/jquery-ui/'},
          {expand: true, cwd: 'bower_components/jstree/dist/', src: ['**'], dest: 'webroot/libs/jstree/'},
          {expand: true, cwd: 'bower_components/pickadate/lib/compressed', src: ['**'], dest: 'webroot/libs/pickadate/'},
          {expand: true, cwd: 'bower_components/respond/dest/', src: ['**'], dest: 'webroot/libs/respond/'},
          {expand: true, cwd: 'bower_components/tinymce/', src: ['**'], dest: 'webroot/libs/tinymce/'},
          {expand: true, cwd: 'webroot/js/tinymce/langs/', src: ['**'], dest: 'webroot/libs/tinymce/langs/'},
          {expand: true, cwd: 'bower_components/underscore/', src: ['*.js'], dest: 'webroot/libs/underscore/'},
          {expand: true, cwd: 'bower_components/flag-icon-css/', src: ['css/**', 'flags/**'], dest: 'webroot/libs/flag-icon-css/'},
          {expand: true, cwd: 'bower_components/sumoselect/', src: ['*.js', '*.css'], dest: 'webroot/libs/sumoselect/'},
          {expand: true, cwd: 'bower_components/select2/', src: ['dist/**'], dest: 'webroot/libs/select2/'},
          {expand: true, cwd: 'bower_components/select2-bootstrap-theme/', src: ['dist/**'], dest: 'webroot/libs/select2-bootstrap-theme/'},
          {expand: true, cwd: 'bower_components/daterangepicker/', src: ['*.js', '*.css', '*.png'], dest: 'webroot/libs/daterangepicker/'}
        ]
      },
      yarn: {
        options: {
          processContentExclude: ['**/*.{png,gif,jpg,ico,psd,ttf,otf,woff,svg}'],
          noProcess: ['**/*.{png,gif,jpg,ico,psd,ttf,otf,woff,woff2,svg}'], // processContentExclude has been renamed to noProcess
          encoding: null
        },
        files: [
          {expand: true, cwd: 'node_modules/toastr/build/', src: ['**'], dest: 'webroot/libs/toastr/'},
          {expand: true, cwd: 'node_modules/sweetalert2/dist/', src: ['**'], dest: 'webroot/libs/sweetalert2/'},
          {expand: true, cwd: 'node_modules/jqvmap/dist/', src: ['**'], dest: 'webroot/libs/jqvmap/'}
        ]
      }
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

  // Default task.
  grunt.registerTask('default', ['less', 'copy']);

};
