<?php

namespace Laravel\Breeze\Console;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

trait InstallsReactRouterStack
{
    /**
     * Install the Inertia React Breeze stack.
     *
     * @return int|null
     */
    protected function installReactRouterStack()
    {
        // Install Sanctum...
        if (! $this->requireComposerPackages(['laravel/sanctum:^4.0'])) {
            return 1;
        }

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@headlessui/react' => '^2.0.0',
                '@tailwindcss/forms' => '^0.5.3',
                '@vitejs/plugin-react' => '^4.2.0',
                'autoprefixer' => '^10.4.12',
                'postcss' => '^8.4.31',
                'tailwindcss' => '^3.2.1',
                'react' => '^18.2.0',
                'react-dom' => '^18.2.0',
                'react-router' => '6.25.1'
            ] + $packages;
        });

        if ($this->option('typescript')) {
            $this->updateNodePackages(function ($packages) {
                return [
                    '@types/node' => '^18.13.0',
                    '@types/react' => '^18.0.28',
                    '@types/react-dom' => '^18.0.10',
                    'typescript' => '^5.0.2',
                ] + $packages;
            });
        }

        // Config
        copy(__DIR__.'/../../stubs/api/config/cors.php', base_path('config/cors.css'));
        copy(__DIR__.'/../../stubs/api/config/sanctum.php', base_path('config/sanctum.css'));

        // Controllers...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/api/app/Http/Controllers', app_path('Http/Controllers'));

        // Requests...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Requests'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/api/app/Http/Requests', app_path('Http/Requests'));

        (new Filesystem)->ensureDirectoryExists(app_path('Http/Middleware'));
        copy(__DIR__.'/../../stubs/api/app/Http/Middleware/EnsureEmailIsVerified.php', app_path('Http/Middleware/EnsureEmailIsVerified.php'));

        // Views...
        copy(__DIR__.'/../../stubs/react-router/resources/views/app.blade.php', resource_path('views/app.blade.php'));

        @unlink(resource_path('views/welcome.blade.php'));

        // Components + Pages...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Routes'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Components'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));

        if ($this->option('typescript')) {
            // TODO: add support for typescript
//            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react-ts/resources/js/Components', resource_path('js/Components'));
//            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react-ts/resources/js/Layouts', resource_path('js/Layouts'));
//            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react-ts/resources/js/Pages', resource_path('js/Pages'));
//            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react-ts/resources/js/types', resource_path('js/types'));
        } else {
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/react-router/resources/js/Components', resource_path('js/Components'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/react-router/resources/js/Layouts', resource_path('js/Layouts'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/react-router/resources/js/Pages', resource_path('js/Pages'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/react-router/resources/js/Routes', resource_path('js/Routes'));
        }

        if (! $this->option('dark')) {
            $this->removeDarkClasses((new Finder)
                ->in(resource_path('js'))
                ->name(['*.jsx', '*.tsx'])
                ->notName(['Welcome.jsx', 'Welcome.tsx'])
            );
        }

        // Tests...
        if (! $this->installTests()) {
            return 1;
        }

        if ($this->option('pest')) {
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/api/pest-tests/Feature', base_path('tests/Feature'));
        } else {
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/api/tests/Feature', base_path('tests/Feature'));
        }

        // Routes...
        copy(__DIR__.'/../../stubs/react-router/routes/api.php', base_path('routes/api.php'));
        copy(__DIR__.'/../../stubs/react-router/routes/auth.php', base_path('routes/auth.php'));
        copy(__DIR__.'/../../stubs/react-router/routes/web.php', base_path('routes/web.php'));

        // Tailwind / Vite...
        copy(__DIR__.'/../../stubs/default/resources/css/app.css', resource_path('css/app.css'));
        copy(__DIR__.'/../../stubs/default/postcss.config.js', base_path('postcss.config.js'));
        copy(__DIR__.'/../../stubs/react-router/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__.'/../../stubs/react-router/vite.config.js', base_path('vite.config.js'));

        if ($this->option('typescript')) {
//            copy(__DIR__.'/../../stubs/inertia-react-ts/tsconfig.json', base_path('tsconfig.json'));
//            copy(__DIR__.'/../../stubs/inertia-react-ts/resources/js/app.tsx', resource_path('js/app.tsx'));

//            if (file_exists(resource_path('js/bootstrap.js'))) {
//                rename(resource_path('js/bootstrap.js'), resource_path('js/bootstrap.ts'));
//            }

//            $this->replaceInFile('"vite build', '"tsc && vite build', base_path('package.json'));
//            $this->replaceInFile('.jsx', '.tsx', base_path('vite.config.js'));
//            $this->replaceInFile('.jsx', '.tsx', resource_path('views/app.blade.php'));
//            $this->replaceInFile('.vue', '.tsx', base_path('tailwind.config.js'));
        } else {
//            copy(__DIR__.'/../../stubs/inertia-common/jsconfig.json', base_path('jsconfig.json'));
            copy(__DIR__.'/../../stubs/inertia-react/resources/js/app.jsx', resource_path('js/app.jsx'));

            $this->replaceInFile('.vue', '.jsx', base_path('tailwind.config.js'));
        }

        if (file_exists(resource_path('js/app.js'))) {
            unlink(resource_path('js/app.js'));
        }

//        if ($this->option('ssr')) {
//            $this->installInertiaReactSsrStack();
//        }

        $this->components->info('Installing and building Node dependencies.');

        if (file_exists(base_path('pnpm-lock.yaml'))) {
            $this->runCommands(['pnpm install', 'pnpm run build']);
        } elseif (file_exists(base_path('yarn.lock'))) {
            $this->runCommands(['yarn install', 'yarn run build']);
        } elseif (file_exists(base_path('bun.lockb'))) {
            $this->runCommands(['bun install', 'bun run build']);
        } else {
            $this->runCommands(['npm install', 'npm run build']);
        }

        $this->line('');
        $this->components->info('Breeze scaffolding installed successfully.');
    }

//    /**
//     * Configure the application JavaScript file to utilize hydrateRoot for SSR.
//     *
//     * @param  string  $path
//     * @return void
//     */
//    protected function configureReactHydrateRootForSsr($path)
//    {
//        $this->replaceInFile(
//            <<<'EOT'
//            import { createRoot } from 'react-dom/client';
//            EOT,
//            <<<'EOT'
//            import { createRoot, hydrateRoot } from 'react-dom/client';
//            EOT,
//            $path
//        );
//
//        $this->replaceInFile(
//            <<<'EOT'
//                    const root = createRoot(el);
//
//                    root.render(<App {...props} />);
//            EOT,
//            <<<'EOT'
//                    if (import.meta.env.DEV) {
//                        createRoot(el).render(<App {...props} />);
//                        return
//                    }
//
//                    hydrateRoot(el, <App {...props} />);
//            EOT,
//            $path
//        );
//    }
}
