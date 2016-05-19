<?php


// Emails.
$app['admin_email'] = 'noreply@wcwd.nothing';
$app['site_email'] = 'noreply@wcwd.nothing';


// Doctrine (db)
$app['db.options'] = array(
    'driver' => 'pdo_mysql',
    'host' => '127.0.0.1',
    'port' => '3306',
    'dbname' => 'wcwd',
    'user' => '',
    'password' => '',
);

