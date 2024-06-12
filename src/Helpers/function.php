<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2024-05-27
 *
 * 成功信息响应函数
 *
 * @param array $data 响应数据
 * @return 成功信息
 */
function success($data = [])
{
  return Response::json([
    'status' => 200,
    'message' => __('message.common.request_success'),
    'data' => $data
  ]);
}


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2024-05-27
 *
 * 错误信息响应函数
 *
 * @param integer $code 错误代码
 * @return 错误信息
 */
function error($message, $code = 1000)
{
  return Response::json([
    'status' => $code,
    'message' => $message
  ]);
}


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2024-05-27
 *
 * 日志响应函数
 *
 * @param object $exception 异常对象
 * @return 错误信息
 */
function record($exception)
{
  if(true == config('app.debug'))
  {
    dd($exception);
  }
  else
  {
    Log::debug($exception);
  }
}
