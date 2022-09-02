module.exports = {
  content: [
      "./templates/**/*.html.twig",
      "./templates/**/**/*.html.twig",
      "./templates/**/**/**/*.html.twig",
      "./templates/bundles/**/**/*.html.twig"
  ],
  darkMode: 'class',
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/line-clamp'),
    require('@tailwindcss/aspect-ratio')
  ],
}
