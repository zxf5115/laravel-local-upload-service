<?php
namespace zxf5115\Upload\Common;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-04-20
 *
 * 公共类
 */
class Common
{
  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-04-20
   *
   * 本地环境下进行日志输出
   *
   * @param    [object]     $exception    [异常对象]
   *
   * @return   [false|错误]
   */
  public static function record(\Exception $exception)
  {
    if('local' == config('app.debug'))
    {
      dd($exception);
    }
    else
    {
      \Log::debug($e);
    }
  }

}
