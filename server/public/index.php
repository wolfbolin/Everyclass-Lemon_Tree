<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/22
 * Time: 16:43
 */

error_reporting(E_ALL);
set_error_handler(function ($severity, $message, $file, $line) {
    if (error_reporting() & $severity) {
        throw new \ErrorException($message, 0, $severity, $file, $line);
    }
});

use Slim\App;

// 引入Composer组建
require __DIR__ . '/../vendor/autoload.php';

// 注册私有工具
require __DIR__ . '/../src/util/http_response.php';

// 注册配置参数
$config = require __DIR__ . '/../src/config.php';
$static = require __DIR__ . '/../src/static.php';
$parameter = array_merge($config, $static);

// Instantiate the app
$app = new App($parameter);

// 注册容器环境
require __DIR__ . '/../src/container.php';

// 注册网络中间件
require __DIR__ . '/../src/middleware.php';

// 注册网络路由
require __DIR__ . '/../src/route/info.php';
require __DIR__ . '/../src/route/mission.php';
require __DIR__ . '/../src/route/cookie.php';
require __DIR__ . '/../src/route/task.php';

// 运行应用程序
try {
    $app->run();
} catch (Exception $e) {
    $container = $app->getContainer();
    $sentry = $container->get('sentry');
    $sentry->captureException($e);
}


