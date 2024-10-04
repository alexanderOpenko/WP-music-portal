/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**/*.{html,js,php}"],
  theme: {
    extend: {
      width: {
        'input-width': 'calc(100% - 18px)',
        'full-content': 'calc(100% - 72px)',
        'full-mobile': 'calc(100% - 40px)',
      },
      minHeight: {
        'container-height': 'calc(100% - 50.5px)'
      },
      maxWidth: {
        '2/4': '50%'
      },
      colors: {
        'mywhite': 'hsl(0deg 0% 100% / 85%)',
        'neutral': '#D7CFC5',
        'submit':  '#4CAF50',
      },
    },
  },
  plugins: [],
  corePlugins: {
    preflight: false,
 }
}

