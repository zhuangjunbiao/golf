var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    var jsAdminGolf = 'admin/golf.js';
    var pathStatic = 'public/static/';

    // admin组
    mix.scripts([jsAdminGolf, 'admin/auth/forget_password.js'], pathStatic+'admin/js/auth/forget_password.js')
        .scripts([jsAdminGolf, 'admin/auth/set_password.js'], pathStatic+'admin/js/auth/set_password.js')
        .scripts([jsAdminGolf, 'admin/auth/login.js'], pathStatic+'admin/js/auth/login.js')
        .scripts([jsAdminGolf, 'admin/auth/modify_password.js'], pathStatic+'admin/js/auth/modify_password.js');

    mix.version([
        pathStatic+'admin/js/auth/forget_password.js',
        pathStatic+'admin/js/auth/set_password.js',
        pathStatic+'admin/js/auth/login.js',
        pathStatic+'admin/js/auth/modify_password.js'
    ]);
});
