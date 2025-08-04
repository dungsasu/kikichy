const { EnvironmentPlugin } = require("webpack");
const mix = require("laravel-mix");
const glob = require("glob");
const path = require("path");

/*
 |--------------------------------------------------------------------------
 | Configure mix
 |--------------------------------------------------------------------------
 */

mix.options({
    resourceRoot: process.env.ASSET_URL || undefined,
    processCssUrls: false,
    postCss: [require("autoprefixer")],
});

/*
 |--------------------------------------------------------------------------
 | Configure Webpack
 |--------------------------------------------------------------------------
 */

mix.webpackConfig({
    output: {
        publicPath: process.env.ASSET_URL || undefined,
        libraryTarget: "umd",
    },

    plugins: [
        new EnvironmentPlugin({
            // Application's public url
            BASE_URL: process.env.ASSET_URL ? `${process.env.ASSET_URL}/` : "/",
        }),
    ],
    module: {
        rules: [
            {
                test: /\.es6$|\.js$/,
                include: [
                    path.join(__dirname, "node_modules/bootstrap/"),
                    path.join(__dirname, "node_modules/popper.js/"),
                    path.join(__dirname, "node_modules/shepherd.js/"),
                ],
                loader: "babel-loader",
                options: {
                    presets: [
                        [
                            "@babel/preset-env",
                            { targets: "last 2 versions, ie >= 10" },
                        ],
                    ],
                    plugins: [
                        "@babel/plugin-transform-destructuring",
                        "@babel/plugin-proposal-object-rest-spread",
                        "@babel/plugin-transform-template-literals",
                    ],
                    babelrc: false,
                },
            },
        ],
    },
    externals: {
        jquery: "jQuery",
        moment: "moment",
        jsdom: "jsdom",
        velocity: "Velocity",
        hammer: "Hammer",
        pace: '"pace-progress"',
        chartist: "Chartist",
        "popper.js": "Popper",

        // blueimp-gallery plugin
        "./blueimp-helper": "jQuery",
        "./blueimp-gallery": "blueimpGallery",
        "./blueimp-gallery-video": "blueimpGallery",
    },
});

/*
 |--------------------------------------------------------------------------
 | Vendor assets
 |--------------------------------------------------------------------------
 */

function mixAssetsDir(query, cb) {
    (glob.sync("resources/assets/" + query) || []).forEach((f) => {
        f = f.replace(/[\\\/]+/g, "/");
        cb(f, f.replace("resources/assets/", "public/assets/"));
    });
}

/*
 |--------------------------------------------------------------------------
 | Configure sass
 |--------------------------------------------------------------------------
 */

const sassOptions = {
    precision: 5,
};

// Core stylesheets
// mixAssetsDir("admin/vendor/scss/**/!(_)*.scss", (src, dest) =>
//     mix.sass(
//         src,
//         dest
//             .replace(/(\\|\/)scss(\\|\/)/, "$1css$2")
//             .replace(/\.scss$/, ".css"),
//         { sassOptions }
//     )
// );

// Core javascripts
// mixAssetsDir("admin/vendor/js/**/*.js", (src, dest) => mix.js(src, dest));

// Libs
// mixAssetsDir("admin/vendor/libs/**/*.js", (src, dest) => mix.js(src, dest));
// mixAssetsDir("admin/vendor/libs/**/!(_)*.scss", (src, dest) =>
//     mix.sass(src, dest.replace(/\.scss$/, ".css"), { sassOptions })
// );
// mixAssetsDir("admin/vendor/libs/**/*.{png,jpg,jpeg,gif}", (src, dest) =>
//     mix.copy(src, dest)
// );

// Fonts
// mixAssetsDir("admin/vendor/fonts/*/*", (src, dest) => mix.copy(src, dest));
// mixAssetsDir("admin/vendor/fonts/!(_)*.scss", (src, dest) =>
//     mix.sass(
//         src,
//         dest
//             .replace(/(\\|\/)scss(\\|\/)/, "$1css$2")
//             .replace(/\.scss$/, ".css"),
//         { sassOptions }
//     )
// );


if (mix.inProduction()) {
    mix.copy('resources/assets/admin/js/ckfinder', 'public/assets/admin/js/ckfinder');

    mixAssetsDir("admin/vendor/scss/**/!(_)*.scss", (src, dest) =>
        mix.sass(
            src,
            dest
                .replace(/(\\|\/)scss(\\|\/)/, "$1css$2")
                .replace(/\.scss$/, ".css"),
            { sassOptions }
        )
    );
    mixAssetsDir("admin/vendor/js/**/*.js", (src, dest) => mix.js(src, dest));

    mixAssetsDir("admin/vendor/libs/**/*.js", (src, dest) => mix.js(src, dest));
    mixAssetsDir("admin/vendor/libs/**/!(_)*.scss", (src, dest) =>
        mix.sass(src, dest.replace(/\.scss$/, ".css"), { sassOptions })
    );
    mixAssetsDir("admin/vendor/libs/**/*.{png,jpg,jpeg,gif}", (src, dest) =>
        mix.copy(src, dest)
    );

    mixAssetsDir("admin/vendor/fonts/*/*", (src, dest) => mix.copy(src, dest));
    mixAssetsDir("admin/vendor/fonts/!(_)*.scss", (src, dest) =>
        mix.sass(
            src,
            dest
                .replace(/(\\|\/)scss(\\|\/)/, "$1css$2")
                .replace(/\.scss$/, ".css"),
            { sassOptions }
        )
    );

    mixAssetsDir("admin/js/**/*.js", (src, dest) => mix.scripts(src, dest));
    mixAssetsDir("admin/css/**/*.scss", (src, dest) =>
        mix.sass(
            src,
            dest
                .replace(/(\\|\/)scss(\\|\/)/, "$1css$2")
                .replace(/\.scss$/, ".css"),
            { sassOptions }
        )
    );

    mixAssetsDir("admin/fonts/**/*.*", (src, dest) => mix.copy(src, dest));
}

mixAssetsDir("client/js/*.js", (src, dest) => mix.scripts(src, dest));

mixAssetsDir("client/css/*.scss", (src, dest) =>
    mix.sass(
        src,
        dest
            .replace(/(\\|\/)scss(\\|\/)/, "$1css$2")
            .replace(/\.scss$/, ".css"),
        { sassOptions }
    )
);

mix.version();

