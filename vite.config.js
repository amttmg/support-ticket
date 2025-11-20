import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: "resources/js/app.js",
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        host: "support-ticket.local", // <-- your subdomain here
        port: 5173, // optional, default 5173
        strictPort: true, // fail if port is busy
        cors: true,
        hmr: {
            host: "support-ticket.local", // important for HMR
        },
    },
    //base: '/support-ticket/public/build/', // Add this line
});
