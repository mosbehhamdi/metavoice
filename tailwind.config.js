const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
       
        
        
        './resources/js/**/*.vue',
       
        "./src/**/*.{vue,js,ts,jsx,tsx}",
        
    ],
    

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    extend: {
        // ...
       listStylePosition: ['hover', 'focus'],
      },
    plugins: [require('@tailwindcss/forms')],
};
