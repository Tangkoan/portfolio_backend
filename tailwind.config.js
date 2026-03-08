import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                // កំណត់ Outfit ជា Font ឡាតាំងគោល និង Nokora ជា Font ខ្មែរ
                sans: ['"Outfit"', ...defaultTheme.fontFamily.sans],
                khmer: ['"Nokora"', 'sans-serif'],
            },
            colors: {
                // រក្សាទុកពណ៌ដើមរបស់អ្នក និងបន្ថែមពណ៌ accent សម្រាប់ Portfolio
                accent: {
                    500: '#3b82f6', 
                    600: '#2563eb',
                },
                primary: 'rgb(var(--color-primary) / <alpha-value>)',
                secondary: 'rgb(var(--color-secondary) / <alpha-value>)',
                'sidebar-bg': 'rgb(var(--sidebar-bg) / <alpha-value>)',
                'sidebar-text': 'rgb(var(--sidebar-text) / <alpha-value>)',
                'header-bg': 'rgb(var(--header-bg) / <alpha-value>)',
                'page-bg': 'rgb(var(--page-bg) / <alpha-value>)',
                'card-bg': 'rgb(var(--card-bg) / <alpha-value>)',
                'input-bg': 'rgb(var(--input-bg) / <alpha-value>)',
                'bor-color': 'rgb(var(--custom-border) / <alpha-value>)',
                'input-border': 'rgb(var(--input-border) / <alpha-value>)',
            },
            animation: {
                'marquee': 'marquee 40s linear infinite',
                'blob': 'blob 10s infinite alternate',
            },
            keyframes: {
                marquee: {
                    '0%': { transform: 'translateX(0%)' },
                    '100%': { transform: 'translateX(-50%)' },
                },
                blob: {
                    '0%': { transform: 'translate(0px, 0px) scale(1)' },
                    '100%': { transform: 'translate(30px, 50px) scale(1.2)' },
                }
            },
            boxShadow: {
                'custom': 'var(--custom-shadow)',
            },
        },
    },
    plugins: [forms],
};