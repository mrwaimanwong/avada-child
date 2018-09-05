// Gulp.js configuration
var gulp = require('gulp');
plugins = require('gulp-load-plugins')({
  pattern: ['gulp-*', 'gulp.*', '*'],
  replaceString: /\bgulp[\-.]/,
      lazy: true,
      camelize: true
    }),

  // folders
  folder = {
    src: 'dev/',
    build: './'
  }
;

// console.log(plugins);

gulp.task('browser-sync', ['css', 'js'], function() {
    plugins.browserSync.init({
        proxy: "http://brickjones.test/",
        // host: 'stracherlaw.waimanwong.local',
        // open: 'external',
        notify: false,
        injectChanges: true

    });
});

// image processing
gulp.task('images', function() {
  var out = folder.build + 'images/';
  return gulp.src(folder.src + 'images/**/*')
    .pipe(plugins.newer(out))
    .pipe(plugins.imagemin({ optimizationLevel: 5 }))
    .pipe(gulp.dest(out));
});

// JavaScript processing
gulp.task('js', function() {

  var jsbuild = gulp.src(folder.src + 'js/**/*')
  .pipe(plugins.deporder())
  .pipe(plugins.concat('scripts.js'))
  // .pipe(plugins.stripDebug())
  .pipe(plugins.uglify())
  .pipe(plugins.rename( { suffix: '.min' } ))
  return jsbuild.pipe(gulp.dest(folder.build + 'js/'));

});

// CSS processing
gulp.task('css', ['images'], function() {

  var postCssOpts = [
  plugins.autoprefixer({ browsers: ['last 2 versions', '> 2%'] }),
  // plugins.cssMqpacker,
  // plugins.cssnano
  ];

  return gulp.src(folder.src + 'scss/main.scss')
    .pipe(plugins.sass({
      outputStyle: 'nested',
      imagePath: 'images/',
      precision: 3,
      errLogToConsole: true
    }))
    .pipe(plugins.postcss(postCssOpts))
    .pipe(plugins.cleanCss())
    .pipe(plugins.rename( { suffix: '.min' } ))
    .pipe(gulp.dest(folder.build + 'css/'))
    .pipe(plugins.browserSync.reload({stream: true}));

});

// watch for changes
gulp.task('watch', ['browser-sync'], function() {

  // image changes
  gulp.watch(folder.src + 'images/**/*', ['images']);

  // php changes
  gulp.watch(folder.build + '**/*.php').on('change', plugins.browserSync.reload);

  // javascript changes
  gulp.watch(folder.src + 'js/**/*', ['js']).on('change', plugins.browserSync.reload);

  // css changes
  gulp.watch(folder.src + 'scss/**/*', ['css']);

});

// run all tasks
// gulp.task('run', ['html', 'css', 'js']);
gulp.task('run', ['css', 'js']);

// default task
gulp.task('default', ['run', 'watch']);
