const path = require("path");
const mix = require("laravel-mix");
require("laravel-mix-merge-manifest");
require("laravel-mix-svg-vue");

mix.alias({
    ziggy: path.resolve("vendor/tightenco/ziggy/dist/vue"),
});

mix.js("resources/Frontend/js/app.js", "public/js")
    .vue({ extractStyles: true })
    .sass("resources/Frontend/scss/app.scss", "public/css/app.css")
    .options({
        processCssUrls: false,
        postCss: [
            require("tailwindcss")("./resources/Frontend/tailwind.config.js"),
            require("autoprefixer"),
        ],
    })
    .extract()
    .webpackConfig({
        resolve: {
            alias: {
                "@": path.resolve("resources/Frontend/js"),
            },
        },
    })
    .mergeManifest()
    .version();

mix.svgVue({
    svgPath: "resources/Frontend/svg",
    extract: false,
    svgoSettings: [
        { removeTitle: true },
        { removeViewBox: false },
        { removeDimensions: false },
    ],
});
