import {defineConfig} from 'vite';
import laravel, {refreshPaths} from 'laravel-vite-plugin';


export default defineConfig({
    build: {
        assetsDir: 'final',
    },
    plugins: [
        laravel({
            input:
                [
                    'resources/sass/app.scss',
                    'resources/js/app.js'
                    // 'resources/css/filament.css'
                ],
            refresh:
                [
                    ...refreshPaths,
                    'app/Livewire/**',
                    'app/Filament/**'
                ]
        }),
    ],
    server: {
        https: {
            key: "/Users/niels/.config/valet/Certificates/freescout.test.key",
            cert:"/Users/niels/.config/valet/Certificates/freescout.test.crt",
        },
        host: 'freescout.test',
        hmr: {
            host: 'freescout.test',
        },
    },
})
;
