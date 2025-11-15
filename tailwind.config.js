module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './resources/css/**/*.css',
  ],
  corePlugins: {
    // Disable preflight to avoid automatic reset of heading margins and fonts
    preflight: false,
  },
  theme: {
    extend: {},
  },
  plugins: [],
};
