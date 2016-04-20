<?php

namespace Phpna\Themes\Providers;

use Pingpong\Themes\ThemesServiceProvider as BaseProvider;
use Illuminate\View\FileViewFinder;
use Phpna\Themes\Src\Theme;
use Phpna\Themes\Src\Repository;
use Phpna\Themes\Src\Finder;

class ThemeServiceProvider extends BaseProvider
{
    /**
     * Register the helpers file.
     */
    public function registerHelpers()
    {
        parent::registerHelpers();
        require __DIR__.'/../src/helpers.php';
    }

    /**
     * Register configuration file.
     */
    protected function registerConfig()
    {
        $builder = $this->app['phpna'];
        $builder->config->mergeConfigFrom(__DIR__.'/../config/themes.php','themes');
        $builder->config->publishDynamics(__DIR__.'/../config/setting.yml','themes.setting');
    }

    /**
     * Register commands.
     */
    protected function registerCommands()
    {
        parent::registerCommands();
        $this->commands('Phpna\Themes\Console\ShellCommand');
        $this->commands('Phpna\Themes\Console\PublishCommand');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app['themes'] = $this->app->share(function ($app) {
            return new Repository(
                new Finder(),
                $app['config'],
                $app['view'],
                $app['translator'],
                $app['cache.store']
            );
        });
        parent::register();
    }

    /**
     * Override view path.
     */
    protected function overrideViewPath()
    {
        $this->app->bind('view.finder', function ($app) {
            $configs = $app['phpna']->config->getDynamics('themes.setting');
            $defaultType = $configs['type'];
            $defaultTheme = $configs['actives'][$defaultType];
            $defaultThemePath = $app['config']['phpna.themes.path'].'/'.$defaultTheme.'/views';

            if (is_dir($defaultThemePath)) {
                $paths = [$defaultThemePath];
            } else {
                $paths = $app['config']['view.paths'];
            }

            return new FileViewFinder($app['files'], $paths);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('themes');
    }
}
