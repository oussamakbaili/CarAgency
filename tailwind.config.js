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
            /**
             * Theme color override:
             * Tailwind orange (e.g. orange-600 == #ea580c) is remapped to #0A66C2.
             */
            colors: {
                orange: {
                    50: '#0A66C2',
                    100: '#0A66C2',
                    200: '#0A66C2',
                    300: '#0A66C2',
                    400: '#0A66C2',
                    500: '#0A66C2',
                    600: '#0A66C2',
                    700: '#0A66C2',
                    800: '#0A66C2',
                    900: '#0A66C2',
                },
            },
        },
    },

    plugins: [forms],
};
