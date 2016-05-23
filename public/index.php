<?php

define('WCWD_PUBLIC_ROOT', __DIR__);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Application.php';

$app = new Dance\Application();

require __DIR__ . '/../app/config/dev.php';
require __DIR__ . '/../src/app.php';
require __DIR__ . '/../src/route.php';

$app->run();