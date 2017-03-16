<?php
/**
 * 引导文件
 */
require __DIR__.'/../vendor/autoload.php';

$app = new \ApiDoc\Application();
$app->run();