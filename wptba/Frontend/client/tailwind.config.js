module.exports = {
	darkMode: 'class',
	content: [
		"./src/**/*.{vue,js,jsx,ts,tsx}",
		"./src/*.{vue,js,jsx,ts,tsx}",
  ],
  theme: {
	  extend: {},
	  
  },
	plugins: [
		require('@tailwindcss/typography'),
	],
}