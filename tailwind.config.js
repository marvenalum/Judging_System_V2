import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                ocean: {
                    DEFAULT: '#0a4d68',
                    light: '#088395',
                },
                mint: '#05bfdb',
                cream: '#fffef7',
                accent: {
                    DEFAULT: '#ff6b35',
                    soft: '#ff8c61',
                },
                dark: {
                    DEFAULT: '#1a1d29',
                    light: '#2d3142',
                },
            },
        },
    },

    plugins: [forms],
};
