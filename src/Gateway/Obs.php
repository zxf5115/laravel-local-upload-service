<?php
namespace zxf5115\Upload\Gateway;

use Illuminate\Support\Facades\Storage;

use ObsV3\ObsClient;
use Goodgay\HuaweiOBS\HWobs;

use zxf5115\Upload\Code;
use zxf5115\Upload\Common\Common;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-04-17
 *
 * 华为云OBS上传模型
 */
class Obs extends Common
{
  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-04-18
   * ------------------------------------------
   * 上传OBS初始化
   * ------------------------------------------
   *
   * 上传OBS初始化
   *
   * @return [type]
   */
  public static function initialize()
  {
    return new self;
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-04-18
   * ------------------------------------------
   * 上传文件到OBS服务器
   * ------------------------------------------
   *
   * 上传文件到OBS服务器
   *
   * @param string $name 文件名
   * @param string $path 路径
   * @param array $allow 允许上传的后缀
   * @param string $is_filename 是否返回文件名
   * @return [type]
   */
  public static function file($name, $path = 'uploads', $allow = [], $is_filename = false)
  {
    try
    {
      // 文件不存在
      if(!request()->hasFile($name))
      {
        return Code::message(Code::FILE_EXIST);
      }

      $file = request()->file($name);

      // 验证失败
      if(!$file->isValid())
      {
        return Code::message(Code::FILE_UPLOAD_ERROR);
      }

      // 过滤所有的.符号
      $path = str_replace('.', '', $path);

      // 先去除两边空格
      $path = trim($path, '/');

      // 获取文件后缀
      $extension = strtolower($file->getClientOriginalExtension());

      // 只能上传规定类型
      if(!empty($allow) && !in_array($extension, $allow))
      {
        return Code::message(Code::FILE_EXTENSION_ERROR);
      }

      $filename = time() . mt_rand(1, 9999999);

      // 组合新的文件名
      $filename = md5($filename) . '.' . $extension;

      // 获取文件资源
      $resource = fopen($file, 'r');

      // 上传文件到OBS服务器
      if(false !== $url = self::wirteObsServer($filename, $resource))
      {
        if($is_filename)
        {
          return [
            'filename' => $file->getClientOriginalName(),
            'url'      => $url
          ];
        }

        return $url;
      }
      else
      {
        return Code::message(Code::FILE_UPLOAD_ERROR);
      }
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      self::record($e);

      return Code::message(Code::FILE_UPLOAD_ERROR);
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-04-18
   * ------------------------------------------
   * 批量上传文件到OBS服务器
   * ------------------------------------------
   *
   * 批量上传文件到OBS服务器
   *
   * @param string $name 文件名
   * @param string $path 路径
   * @param array $allow 允许上传的后缀
   * @param string $is_filename 是否返回文件名
   * @return [type]
   */
  public static function batchRichTextFile($name, $path = 'uploads', $allow = [], $is_filename = false)
  {
    try
    {
      $response = [];

      if(!request()->hasFile($name))
      {
        return Code::message(Code::FILE_EXIST);
      }

      // 获取上传文件数组
      $files = request()->file($name);

      // 批量上传
      foreach($files as $k => $file)
      {
        if(!$file->isValid())
        {
          return Code::message(Code::FILE_UPLOAD_ERROR);
        }

        // 过滤所有的.符号
        $path = str_replace('.', '', $path);

        // 先去除两边空格
        $path = trim($path, '/');

        // 获取文件后缀
        $extension = strtolower($file->getClientOriginalExtension());

        // 只能上传规定类型
        if(!empty($allow) && !in_array($extension, $allow))
        {
          return Code::message(Code::FILE_EXTENSION_ERROR);
        }

        $filename = time() . mt_rand(1, 9999999);

        // 组合新的文件名
        $filename = md5($filename) . '.' . $extension;

        // 获取文件资源
        $resource = fopen($file, 'r');

        // 上传文件到OBS服务器
        if(false !== $url = self::wirteObsServer($filename, $resource))
        {
          if($is_filename)
          {
            $response[$k]['filename'] = $file->getClientOriginalName();
            $response[$k]['url']      = $url;
          }
          else
          {
            $response[$k] = $url;
          }
        }
        else
        {
          return Code::message(Code::FILE_UPLOAD_ERROR);
        }
      }

      return $response;
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      self::record($e);

      return Code::message(Code::FILE_UPLOAD_ERROR);
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-04-18
   * ------------------------------------------
   * 上传文件到OBS服务器（base64）
   * ------------------------------------------
   *
   * 上传文件到OBS服务器（base64）
   *
   * @param string $file 数据内容
   * @param string $path 路径
   * @param array $allow 允许上传的后缀
   * @param string $name 文件名
   * @return [type]
   */
  public static function file_base64($file, $path = 'uploads', $allow = [], $name = false)
  {
    try
    {
      // 文件不存在
      if(empty($file))
      {
        return Code::message(Code::FILE_EXIST);
      }

      // 判断当前资源是什么
      if(false !==strpos($file, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'))
      {
        // 替换编码头
        preg_match('/^(data:application\/vnd.openxmlformats-officedocument.wordprocessingml.document;base64,)/', $file, $data);
        $data[2] = 'docx';
      }
      else if(false !==strpos($file, 'application/msword'))
      {
        // 替换编码头
        preg_match('/^(data:application\/msword;base64,)/', $file, $data);
        $data[2] = 'doc';
      }
      else if(false !==strpos($file, 'application/vnd.ms-excel application/x-excel'))
      {
        // 替换编码头
        preg_match('/^(data:application\/vnd.ms-excel application\/x-excel;base64,)/', $file, $data);
        $data[2] = 'xls';
      }
      else if(false !==strpos($file, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'))
      {
        // 替换编码头
        preg_match('/^(data:application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,)/', $file, $data);
        $data[2] = 'xlsx';
      }
      else if(false !==strpos($file, 'application/pdf'))
      {
        // 替换编码头
        preg_match('/^(data:application\/pdf;base64,)/', $file, $data);
        $data[2] = 'pdf';
      }
      else if(false !==strpos($file, 'application/octet-stream'))
      {
        // 替换编码头
        preg_match('/^(data:application\/octet-stream;base64,)/', $file, $data);
        $data[2] = 'xlsx';
      }
      else if(false !==strpos($file, 'audio/mp3'))
      {
        // 替换编码头
        preg_match('/^(data:audio\/mp3;base64,)/', $file, $data);
        $data[2] = 'mp3';
      }
      else
      {
        // 替换编码头
        preg_match('/^(data:\s*image\/(\w+);base64,)/', $file, $data);
      }

      // 去除文件头，并且解析base64数据
      $file = base64_decode(str_replace($data[1], '', $file));

      // 过滤所有的.符号
      $path = str_replace('.', '', $path);

      // 先去除两边空格
      $path = trim($path, '/');

      // 获取文件后缀
      $extension = $data[2];

      // 只能上传规定类型
      if(!empty($allow) && !in_array($extension, $allow))
      {
        return Code::message(Code::FILE_EXTENSION_ERROR);
      }

      $filename = time() . mt_rand(1, 9999999);

      // 组合新的文件名
      $newName = md5($filename) . '.' . $extension;

      // 组装新的文件名
      $dir = $path . DIRECTORY_SEPARATOR . date('Y-m-d');

      // 创建目录
      Storage::disk('hwobs')->makeDirectory($dir);

      $filename = $dir . DIRECTORY_SEPARATOR . $newName;

      // 将内容上传到本地
      if(Storage::disk('public')->put($filename, $file))
      {
        // 获取上传内容资源信息
        $url =  public_path(Storage::url($filename));

        $resource = fopen($url, 'r');

        // 将本地资源删除
        Storage::disk('public')->delete($filename);

        // 将资源上传到OBS服务器
        $url = self::wirteObsServer($filename, $resource);

        if($name)
        {
          return [
            'filename' => $name,
            'url'      => $url
          ];
        }

        return $url;
      }

      return Code::message(Code::FILE_UPLOAD_ERROR);
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      self::record($e);

      return Code::message(Code::FILE_UPLOAD_ERROR);
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-04-18
   * ------------------------------------------
   * 将资源上传到华为OBS服务器
   * ------------------------------------------
   *
   * 将资源上传到华为OBS服务器
   *
   * @param [type] $filename 文件名
   * @param [type] $resource 文件资源
   * @return [type]
   */
  private static function wirteObsServer($filename, $resource)
  {
    try
    {
      $response = Storage::disk('hwobs')->writeStream($filename, $resource);

      if($response)
      {
        $url = Storage::disk('hwobs')->url($filename);

        return strstr($url, '?', true);
      }
      else
      {
        return false;
      }
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      self::record($e);

      return false;
    }
  }
}
