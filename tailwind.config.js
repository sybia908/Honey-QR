module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        amber: {
          50: '#FFF8E8',
          100: '#FFE0A3',
          200: '#FFC56E',
          400: '#F2A93B',
          600: '#E09422',
          700: '#4A3500',
          800: '#332400',
        },
      },
    },
  },
  plugins: [],
}
