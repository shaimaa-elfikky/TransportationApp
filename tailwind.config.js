import defaultTheme from 'tailwindcss/defaultTheme';
// ... other imports for plugins

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './vendor/filament/**/*.blade.php',
        './app/Filament/**/*.php',
        './storage/framework/views/*.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans], // Ensure 'Inter' is imported/available
            },
            colors: {
                primary: {
                    50: '#F0FBFF',
                    100: '#E0F8FF',
                    200: '#BAF0FF',
                    300: '#7CDDFF',
                    400: '#3CC2FF',
                    500: '#0EA8E9',
                    600: '#0A88BC',
                    700: '#086A91',
                    800: '#064B66',
                    900: '#042C3B',
                    950: '#021B27',
                },
                accent: {
                    50: '#FFFBEB',
                    100: '#FEF3C7',
                    200: '#FDE68A',
                    300: '#FCD34D',
                    400: '#FBBF24',
                    500: '#F59E0B',
                    600: '#D97706',
                    700: '#B45309',
                    800: '#92400E',
                    900: '#78350F',
                    950: '#451A03',
                },
                lightgray: {
                    50: '#F9FAFB',
                    100: '#F3F4F6',
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};