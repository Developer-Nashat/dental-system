import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import svgLoader from 'vite-svg-loader';

export default defineConfig({
    plugins: [
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
			},
		 }),

        laravel({
             input: ['resources/css/app.css', 'resources/js/app.ts'],
             refresh: true,
         }),

        svgLoader(),
    ],
});
