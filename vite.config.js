import { defineConfig, loadEnv } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), "");

    const isLocal = mode === "development";

    return {
        base: env.VITE_BASE_URL || "/", // auto for server or subfolder
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

        // Only apply this in local development
        ...(isLocal
            ? {
                  server: {
                      host: "support-ticket.local",
                      port: 5173,
                      strictPort: true,
                      cors: true,
                      hmr: {
                          host: "support-ticket.local",
                      },
                  },
              }
            : {}),
    };
});
