// vite.config.js
import { defineConfig } from "file:///F:/Apps/Web%20Apps/Laravel/dental-app/node_modules/vite/dist/node/index.js";
import laravel from "file:///F:/Apps/Web%20Apps/Laravel/dental-app/node_modules/laravel-vite-plugin/dist/index.js";
import vue from "file:///F:/Apps/Web%20Apps/Laravel/dental-app/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import svgLoader from "file:///F:/Apps/Web%20Apps/Laravel/dental-app/node_modules/vite-svg-loader/index.js";
var vite_config_default = defineConfig({
  plugins: [
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false
        }
      }
    }),
    laravel({
      input: ["resources/css/app.css", "resources/js/app.ts"],
      refresh: true
    }),
    svgLoader()
  ]
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJGOlxcXFxBcHBzXFxcXFdlYiBBcHBzXFxcXExhcmF2ZWxcXFxcZGVudGFsLWFwcFwiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiRjpcXFxcQXBwc1xcXFxXZWIgQXBwc1xcXFxMYXJhdmVsXFxcXGRlbnRhbC1hcHBcXFxcdml0ZS5jb25maWcuanNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL0Y6L0FwcHMvV2ViJTIwQXBwcy9MYXJhdmVsL2RlbnRhbC1hcHAvdml0ZS5jb25maWcuanNcIjtpbXBvcnQgeyBkZWZpbmVDb25maWcgfSBmcm9tICd2aXRlJztcbmltcG9ydCBsYXJhdmVsIGZyb20gJ2xhcmF2ZWwtdml0ZS1wbHVnaW4nO1xuaW1wb3J0IHZ1ZSBmcm9tICdAdml0ZWpzL3BsdWdpbi12dWUnO1xuaW1wb3J0IHN2Z0xvYWRlciBmcm9tICd2aXRlLXN2Zy1sb2FkZXInO1xuXG5leHBvcnQgZGVmYXVsdCBkZWZpbmVDb25maWcoe1xuICAgIHBsdWdpbnM6IFtcbiAgICAgICAgdnVlKHtcbiAgICAgICAgICAgIHRlbXBsYXRlOiB7XG4gICAgICAgICAgICAgICAgdHJhbnNmb3JtQXNzZXRVcmxzOiB7XG4gICAgICAgICAgICAgICAgICAgIGJhc2U6IG51bGwsXG4gICAgICAgICAgICAgICAgICAgIGluY2x1ZGVBYnNvbHV0ZTogZmFsc2UsXG4gICAgICAgICAgICAgICAgfSxcblx0XHRcdH0sXG5cdFx0IH0pLFxuXG4gICAgICAgIGxhcmF2ZWwoe1xuICAgICAgICAgICAgIGlucHV0OiBbJ3Jlc291cmNlcy9jc3MvYXBwLmNzcycsICdyZXNvdXJjZXMvanMvYXBwLnRzJ10sXG4gICAgICAgICAgICAgcmVmcmVzaDogdHJ1ZSxcbiAgICAgICAgIH0pLFxuXG4gICAgICAgIHN2Z0xvYWRlcigpLFxuICAgIF0sXG59KTtcbiJdLAogICJtYXBwaW5ncyI6ICI7QUFBdVMsU0FBUyxvQkFBb0I7QUFDcFUsT0FBTyxhQUFhO0FBQ3BCLE9BQU8sU0FBUztBQUNoQixPQUFPLGVBQWU7QUFFdEIsSUFBTyxzQkFBUSxhQUFhO0FBQUEsRUFDeEIsU0FBUztBQUFBLElBQ0wsSUFBSTtBQUFBLE1BQ0EsVUFBVTtBQUFBLFFBQ04sb0JBQW9CO0FBQUEsVUFDaEIsTUFBTTtBQUFBLFVBQ04saUJBQWlCO0FBQUEsUUFDckI7QUFBQSxNQUNiO0FBQUEsSUFDQSxDQUFDO0FBQUEsSUFFSSxRQUFRO0FBQUEsTUFDSCxPQUFPLENBQUMseUJBQXlCLHFCQUFxQjtBQUFBLE1BQ3RELFNBQVM7QUFBQSxJQUNiLENBQUM7QUFBQSxJQUVGLFVBQVU7QUFBQSxFQUNkO0FBQ0osQ0FBQzsiLAogICJuYW1lcyI6IFtdCn0K
