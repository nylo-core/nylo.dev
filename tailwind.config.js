module.exports = {
  content: [
  "./resources/**/*.blade.php",
  "./resources/**/*.js",
  "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      typography: {

        DEFAULT: {
          css: {
            code: {
              '&:before': {
                content: 'none !important',
              },
              '&:after': {
                content: 'none !important',
              },
              color: '#4f51db',
              backgroundColor: '#f1f5f8',
              padding: '0.25rem',
              borderRadius: '0.25rem',
              fontWeight: 400,
            },
            pre: {
              code: {
                backgroundColor: '#1f2937',
                color: '#e5e7eb',
                padding: 0,
              },
            },
          },
        }
      },
    },
  },
  plugins: [
  require('@tailwindcss/typography'),
  ],
}
