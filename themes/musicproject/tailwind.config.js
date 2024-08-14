/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**/*.{html,js,php}"],
  theme: {
    extend: {
      width: {
        'input-width': 'calc(100% - 18px)',
      },
      colors: {
        'mywhite': 'hsl(0deg 0% 100% / 85%)',
      },
    },
  },
  plugins: [],
  corePlugins: {
    preflight: false,
 }
}

