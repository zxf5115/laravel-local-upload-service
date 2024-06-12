<?php
namespace Zxf5115\Laravel\Local\Upload\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

use Zxf5115\Laravel\Local\Upload\Services\FileService;

/**
 * 文件操作服务提供器类
 */
class FileServiceProvider extends ServiceProvider implements DeferrableProvider
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
    // 注册单例服务
    $this->app->singleton('File', function($app){
        return new FileService;
    });

    // 设置别名
    $this->app->alias('File', FileService::class);
  }


  /**
   * 引导服务
   */
  public function boot()
  {
    // 设置配置信息
    $this->setupConfig();

    // 设置语言信息
    $this->setupLanguage();
  }



  /**
   * Get the services provided by the provider.
   *
   * @return array
   */
  public function provides()
  {
    return [FileService::class];
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2024-06-12
   *
   * 设置配置信息
   *
   * @return [type]
   */
  private function setupConfig()
  {
    $path = __DIR__ . '/../../config/config.php';

    // 加载配置文件
    $this->publishes([
      $path => config_path('zxf5115.php')
    ], 'zxf5115');

    $this->mergeConfigFrom($path, 'zxf5115');
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2024-06-12
   *
   * 设置语言信息
   *
   * @return [type]
   */
  private function setupLanguage()
  {
    $path = __DIR__ . '/../../lang';

    // 加载语言文件
    $this->loadTranslationsFrom($path, 'zxf5115');
  }
}
