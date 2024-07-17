/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**/*.{html,js,php}"],
  theme: {
    extend: {
      width: {
        'input-width': 'calc(100% - 18px)',
      }
    },
  },
  plugins: [],
  corePlugins: {
    preflight: false,
 }
}

