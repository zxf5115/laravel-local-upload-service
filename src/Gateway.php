<?php
namespace zxf5115\Upload;

use zxf5115\Upload\Gateway\Obs;
use zxf5115\Upload\Gateway\Oss;
use zxf5115\Upload\Gateway\Local;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-04-17
 *
 * 上传文件存储类型模型
 */
class Gateway
{
  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-04-18
   * ------------------------------------------
   * 获取存储类型
   * ------------------------------------------
   *
   * 获取存储类型
   *
   * @return [type]
   */
  public static function getGateway()
  {
    $type = config('upload.type');

    if(1 == $type)
    {
      return Local::initialize();
    }
    else if(2 == $type)
    {
      return Oss::initialize();
    }
    else if(3 == $type)
    {
      return Obs::initialize();
    }
  }
}
