let mix = require('laravel-mix');

mix.js("src/main.js", "dist")
	.vue()
  .postCss("src/main.css", "dist", [
    require("tailwindcss"),
  ]);