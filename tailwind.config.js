const colors = require('tailwindcss/colors');

// Kroxy B&W theme - pure black and white palette
const gray = {
    50:  'hsl(0, 0%, 97%)',
    100: 'hsl(0, 0%, 91%)',
    200: 'hsl(0, 0%, 80%)',
    300: 'hsl(0, 0%, 65%)',
    400: 'hsl(0, 0%, 50%)',
    500: 'hsl(0, 0%, 38%)',
    600: 'hsl(0, 0%, 28%)',
    700: 'hsl(0, 0%, 18%)',
    800: 'hsl(0, 0%, 11%)',
    900: 'hsl(0, 0%, 6%)',
};

module.exports = {
    content: [
        './resources/scripts/**/*.{js,ts,tsx}',
    ],
    theme: {
        extend: {
            fontFamily: {
                header: ['"IBM Plex Sans"', '"Roboto"', 'system-ui', 'sans-serif'],
            },
            colors: {
                black: '#000000',
                primary: {
                    50:  'hsl(0, 0%, 97%)',
                    100: 'hsl(0, 0%, 91%)',
                    200: 'hsl(0, 0%, 80%)',
                    300: 'hsl(0, 0%, 65%)',
                    400: 'hsl(0, 0%, 50%)',
                    500: 'hsl(0, 0%, 38%)',
                    600: 'hsl(0, 0%, 28%)',
                    700: 'hsl(0, 0%, 18%)',
                    800: 'hsl(0, 0%, 11%)',
                    900: 'hsl(0, 0%, 6%)',
                },
                gray: gray,
                neutral: gray,
                cyan: {
                    50:  '#f9fafb',
                    100: '#f3f4f6',
                    200: '#e5e7eb',
                    300: '#d1d5db',
                    400: '#9ca3af',
                    500: '#6b7280',
                    600: '#ffffff',
                    700: '#374151',
                    800: '#1f2937',
                    900: '#111827',
                },
            },
            fontSize: {
                '2xs': '0.625rem',
            },
            transitionDuration: {
                250: '250ms',
            },
            borderColor: theme => ({
                default: theme('colors.neutral.400', 'currentColor'),
            }),
        },
    },
    plugins: [
        require('@tailwindcss/line-clamp'),
        require('@tailwindcss/forms')({
            strategy: 'class',
        }),
    ]
};
