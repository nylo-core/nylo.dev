import { addIconSelectors } from '@iconify/tailwind';

/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
  content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
  ],
  theme: {
    fontFamily: {
        'Work sans': ['Work Sans', 'sans-serif'],
        'Outfit': ['Outfit', 'sans-serif'],
        'Sora': ['Sora', 'sans-serif'],
        'sans': ['Work Sans', 'sans-serif'],
      },
    extend: {
      typography: {
        DEFAULT: {
          css: {
            blockquote: {
              p: {
                '&:before': {
                  content: 'none',
                },
                fontStyle: 'normal !important',
                marginTop: '3px',
                marginBottom: '3px',
                color: '#374151',
              }
            },
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
      backdropBlur: {
        xs: '2px',
      },
      backgroundImage: {
        'hero-pattern': "url('/images/hero_fade.png')"
      }
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    addIconSelectors(['simple-icons', 'lucide']),
  ],
}
