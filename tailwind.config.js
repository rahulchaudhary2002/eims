import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
        "./resources/js/**/*.ts",
        "./resources/js/**/*.tsx",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter", "Nunito Sans", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: "#2563EB",
                    50:  "#EFF6FF",
                    100: "#DBEAFE",
                    200: "#BFDBFE",
                    300: "#93C5FD",
                    400: "#60A5FA",
                    500: "#3B82F6",
                    600: "#2563EB",
                    700: "#1D4ED8",
                    800: "#1E40AF",
                    900: "#1E3A8A",
                },
                sky: {
                    DEFAULT: "#0EA5E9",
                    50:  "#F0F9FF",
                    100: "#E0F2FE",
                    200: "#BAE6FD",
                    300: "#7DD3FC",
                    400: "#38BDF8",
                    500: "#0EA5E9",
                    600: "#0284C7",
                    700: "#0369A1",
                    800: "#075985",
                    900: "#0C4A6E",
                },
                success: {
                    DEFAULT: "#16A34A",
                    light:   "#DCFCE7",
                    dark:    "#15803D",
                },
                warning: {
                    DEFAULT: "#F59E0B",
                    light:   "#FEF3C7",
                    dark:    "#D97706",
                },
                danger: {
                    DEFAULT: "#DC2626",
                    light:   "#FEE2E2",
                    dark:    "#B91C1C",
                },
                info: {
                    DEFAULT: "#0284C7",
                    light:   "#E0F2FE",
                    dark:    "#0369A1",
                },
            },
            borderRadius: {
                card:   "12px",
                button: "10px",
                input:  "10px",
                modal:  "20px",
            },
            boxShadow: {
                soft:   "0 2px 8px rgba(15, 23, 42, 0.08)",
                medium: "0 8px 24px rgba(15, 23, 42, 0.12)",
                strong: "0 12px 32px rgba(15, 23, 42, 0.18)",
            },
            maxWidth: {
                container: "1280px",
                wide:      "1320px",
            },
            height: {
                header:       "84px",
                "btn-default": "48px",
                "search-input": "56px",
            },
            minHeight: {
                "search-input": "56px",
            },
        },
    },

    plugins: [forms, typography],
};
