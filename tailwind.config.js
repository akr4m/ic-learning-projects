/**
 * Tailwind CSS Configuration
 *
 * এই ফাইলে Tailwind CSS এর কাস্টম কনফিগারেশন সংজ্ঞায়িত করা হয়েছে।
 *
 * Font Configuration:
 * - Figtree: Primary English font
 * - Noto Sans Bengali: বাংলা ফন্ট সাপোর্টের জন্য
 *
 * @see https://tailwindcss.com/docs/configuration
 */

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
                // Sans font stack with Bengali support
                // Figtree for English, Noto Sans Bengali for বাংলা
                sans: [
                    'Figtree',
                    'Noto Sans Bengali',
                    ...defaultTheme.fontFamily.sans
                ],
            },
        },
    },

    plugins: [forms],
};
