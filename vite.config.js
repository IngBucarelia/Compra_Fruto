import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
  plugins: [
    vue(),
    laravel({
      input: ['resources/js/offline-app.js', 'resources/css/app.css'],
      refresh: true,
    })
  ],
  build: {
    rollupOptions: {
      input: {
        offline: 'resources/js/offline-app.js'
      },
      
    },
    outDir: 'public/build'
  }
})
