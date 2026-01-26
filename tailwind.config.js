/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./wp-content/themes/kikemonk/**/*.{php,html,js}"],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        'brand-blue': '#1e3a8a',
        'brand-gold': '#d97706',
      },
      fontFamily: {
        'sans': ['Inter', 'sans-serif'],
      },
      backgroundImage: {
        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
        'gradient-conic': 'conic-gradient(from 180deg, var(--tw-gradient-stops))',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
