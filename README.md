## laravel-local-upload-service

 - A Laravel Plugin For Encapsulation Local Upload Services.

----------

### Requirement

 - PHP >= 7.0
 - laravel >= 6.0
 - illuminate/support

----------

### Installation

 - installing plugins package

```shell
composer require "zxf5115/laravel-local-upload-service"
```




# laravel-upload
为系统开发上传组件，测试阶段（不建议使用）

## Requirement

1. PHP >= 7.2
2. **[Composer](https://getcomposer.org/)**
3. goodgay/huaweiobs
4.

## Installation

```shell
$ composer require "zxf5115/laravel-upload"
```
app.php 中 providers 下添加：

```php
zxf5115\Upload\FileServiceProvider::class
```

发布配置文件
```php
php artisan vendor:publish
```

## Usage

基本使用（以服务端为例）:

```php
<?php

use zxf5115\Upload\File;

$category = $request->category ?? 'file';

$response = File::file('file', $category);
```
