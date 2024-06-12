<?php
namespace zxf5115\Upload\Gateway;

use Illuminate\Support\Facades\Storage;

use zxf5115\Upload\Code;
use zxf5115\Upload\Common\Common;


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-04-20
 *
 * 上传文件到本地模型
 */
class Local extends Common
{
  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-04-18
   * ------------------------------------------
   * 上传本地初始化
   * ------------------------------------------
   *
   * 上传本地初始化
   *
   * @return [type]
   */
  public static function initialize()
  {
    return new self;
  }

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-04-17
   * ------------------------------------------
   * 上传文件
   * ------------------------------------------
   *
   * 上传文件
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

      // 组装目录名
      $path = $path . DIRECTORY_SEPARATOR . date('Y-m-d');

      $filename = $file->getClientOriginalName();

      // 保存文件
      if($url = Storage::disk('public')->putFileAs($path, $file, $filename))
      {
        if($is_filename)
        {
          return [
            'filename' => $file->getClientOriginalName(),
            'url'      => self::getCompleteUrl($url)
          ];
        }

        return self::getCompleteUrl($url);
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
   * @dateTime 2020-02-24
   * ------------------------------------------
   * 批量上传文件
   * ------------------------------------------
   *
   * 批量上传文件
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

      // 文件不存在
      if(!request()->hasFile($name))
      {
        return Code::message(Code::FILE_EXIST);
      }

      // 获取上传文件数组
      $files = request()->file($name);

      // 批量上传
      foreach($files as $k => $file)
      {
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

        // 组装目录
        $path = $path . DIRECTORY_SEPARATOR . date('Y-m-d');

        // 保存文件
        if($url = Storage::disk('public')->putFile($path, $file))
        {
          if($is_filename)
          {
            $response[$k]['filename'] = $file->getClientOriginalName();
            $response[$k]['url']      = self::getCompleteUrl($url);
          }
          else
          {
            $response[$k] = self::getCompleteUrl($url);
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
   * @dateTime 2021-04-16
   * ------------------------------------------
   * Base64文件流上传文件
   * ------------------------------------------
   *
   * Base64文件流上传文件
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
      else if(false !==strpos($file, 'image'))
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

      // 文件名
      $filename = time() . mt_rand(1, 9999999);

      // 组装新的文件名
      $newName = md5($filename) . '.' . $extension;

      // 组装目录名
      $dir = $path . DIRECTORY_SEPARATOR . date('Y-m-d');

      // 创建目录
      Storage::disk('public')->makeDirectory($dir);

      // 组装文件名
      $filename = $dir . DIRECTORY_SEPARATOR . $newName;

      // 保存文件
      if(Storage::disk('public')->put($filename, $file))
      {
        $url = Storage::url($filename);

        // 直接返回文件名
        if($name)
        {
          return [
            'filename' => $name,
            'url'      => self::getCompleteUrl($url, 2)
          ];
        }

        return self::getCompleteUrl($url, 2);
      }
      else
      {
        return Code::message(Code::FILE_UPLOAD_ERROR);
      }
    }
    catch(\Exception $e)
    {
      self::record($e);

      return Code::message(Code::FILE_UPLOAD_ERROR);
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-04-18
   * ------------------------------------------
   * 组装Url完整地址
   * ------------------------------------------
   *
   * 组装Url完整地址
   *
   * @param [type] $url 基础路由
   * @param [type] $type 上传类型 1 普通 2 base64
   * @return [type]
   */
  public static function getCompleteUrl($url, $type = 1)
  {
    try
    {
      $base_url = config('upload.base_url');

      if($type == 2)
      {
        return $base_url . $url;
      }

      return $base_url . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR .  $url;
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      self::record($e);

      return false;
    }
  }
}
