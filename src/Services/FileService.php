<?php
namespace Zxf5115\Laravel\Local\Upload\Services;

use zxf5115\Upload\Gateway;

/**
 * 文件操作服务类
 */
class FileService
{
  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2024-06-12
   *
   * 上传文件
   *
   * @param string $file 文件
   * @param string $path 路径
   * @param string $allow 允许上传的后缀
   * @param string $is_filename 是否返回文件名
   * @return [type]
   */
  public static function file($file, $path = 'uploads', $allow = false, $is_filename = false)
  {
    try
    {
      if(!$allow)
      {
        $allow = ['docx', 'doc', 'xls', 'xlsx', 'pdf', 'mp3', 'mp4'];
      }

      $gateway = Gateway::getGateway();

      return $gateway::file($file, $path, $allow, $is_filename);
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      record($e);

      return false;
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-04-18
   *
   * 上传文件(base64)
   *
   * @param string $file 文件
   * @param string $path 路径
   * @param string $allow 允许上传的后缀
   * @param string $name 文件名
   * @return [type]
   */
  public static function file_base64($file, $path = 'uploads', $allow = false, $name = false)
  {
    try
    {
      if(!$allow)
      {
        $allow = ['docx', 'doc', 'xls', 'xlsx', 'pdf', 'mp3', 'mp4'];
      }

      $gateway = Gateway::getGateway();

      return $gateway::file_base64($file, $path, $allow, $name);
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      record($e);

      return false;
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-04-18
   *
   * 上传图片
   *
   * @param string $file 文件
   * @param string $path 路径
   * @param string $allow 允许上传的后缀
   * @param string $is_filename 是否返回文件名
   * @return [type]
   */
  public static function picture($file, $path = 'uploads', $allow = false, $is_filename = false)
  {
    try
    {
      if(!$allow)
      {
        $allow = ['jpg', 'jpeg', 'png', 'gif'];
      }

      $gateway = Gateway::getGateway();

      return $gateway::file($file, $path, $allow, $is_filename);
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      record($e);

      return false;
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-04-18
   *
   * 上传图片(base64)
   *
   * @param string $file 文件
   * @param string $path 路径
   * @param string $allow 允许上传的后缀
   * @param string $name 文件名
   * @return [type]
   */
  public static function picture_base64($file, $path = 'uploads', $allow = false, $name = false)
  {
    try
    {
      if(!$allow)
      {
        $allow = ['jpg', 'jpeg', 'png', 'gif'];
      }

      $gateway = Gateway::getGateway();

      return $gateway::file_base64($file, $path, $allow, $name);
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      record($e);

      return false;
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-07-13
   *
   * 批量上传图片
   *
   * @param string $name 文件名
   * @param string $path 路径
   * @return [type]
   */
  public static function batchRichTextFile($name, $path = 'uploads')
  {
    try
    {
      $allow = ['jpg', 'jpeg', 'png', 'gif'];

      $gateway = Gateway::getGateway();

      return $gateway::batchRichTextFile($name, $path, $allow);
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      record($e);

      return false;
    }
  }
}
