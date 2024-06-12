<?php
namespace zxf5115\Upload;

use Illuminate\Support\ServiceProvider;

class FileServiceProvider extends ServiceProvider
{
    /**
     * 如果延时加载，$defer 必须设置为 true
     */
    protected $defer = true;


    /**
     * 注册服务
     */
    public function register()
    {
        $this->app->bind('upload', function ()
        {
            return new Upload();
        });

        $this->app->alias('upload', Upload::class);
    }


    /**
     * 引导服务
     */
    public function boot()
    {
        $path = __DIR__ . '/../config/config.php';

        $this->mergeConfigFrom($path, 'upload');

        $this->publishes([
            $path => config_path('upload.php')
        ], 'upload');
    }



    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['upload', Upload::class];
    }
}
