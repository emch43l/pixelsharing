/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    screens: {
      'home-md' : '800px',
      'home-lg' : '1080px'
    },
    extend: {},
  },
  plugins: [
      require('daisyui')
  ],
}
