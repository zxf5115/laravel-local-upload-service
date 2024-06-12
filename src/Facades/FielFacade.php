<?php
namespace Zxf5115\Laravel\Local\Upload\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 文件上传静态代理类
 */
class FielFacade extends Facade
{
  protected static function getFacadeAccessor()
  {
    return 'File';
  }
}
