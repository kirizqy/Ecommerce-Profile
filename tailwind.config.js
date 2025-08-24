export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
  ],
  theme: {
    extend: {
      colors: {
        primary: '#ff7b00', // warna utama
      },
      fontFamily: {
        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
    },
  },
  corePlugins: {
    preflight: false, // ganti fungsi @config { preflight: false }
  },
  plugins: [],
}
