import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
  plugins: [tailwindcss(),react()],
  build: {
    outDir: '../assets/react-build',
    emptyOutDir: true,
  },
})
