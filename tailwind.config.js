/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    screens: {
      'home-md' : '800px',
      'home-lg' : '1080px',
      ...defaultTheme.screens,
    },
    extend: {},
  },
  plugins: [
      require('daisyui')
  ],
}
