import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Poppins", ...defaultTheme.fontFamily.sans],
            },
            animation: {
                "fade-in-slow": "fadeIn 2s ease-out",
                "slide-in-left": "slideinLeft 1.5s ease-out",
                "slide-in-right": "slideinRight 1.5s ease-out",
                "bounce-slow": "bounceSlowly 2s ease-in-out infinite",
            },
        },
    },

    plugins: [forms],
};
