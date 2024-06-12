<?php
namespace zxf5115\Upload;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-04-20
 *
 * 错误类
 */
class Code
{
  // 上传文件为空
  const FILE_EXIST           = 6000;

  // 文件类型不被允许
  const FILE_EXTENSION_ERROR = 6001;

  // 上传失败
  const FILE_UPLOAD_ERROR    = 6002;


  /**
   * 错误信息数组
   */
  public static $message = [
    self::FILE_EXIST             => '上传文件为空',
    self::FILE_EXTENSION_ERROR   => '文件类型不被允许',
    self::FILE_UPLOAD_ERROR      => '上传失败',
  ];


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-04-20
   * ------------------------------------------
   * 组装Code对应显示内容
   * ------------------------------------------
   *
   * 组装Code对应显示内容
   *
   * @param int $code 信息代码
   * @return 信息内容
   */
  public static function message($code)
  {
    return self::$message[$code] ?: self::$message[self::FILE_UPLOAD_ERROR];
  }
}
