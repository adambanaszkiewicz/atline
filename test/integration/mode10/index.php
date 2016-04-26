<?php

include '../load.php';
include 'MyEnvironment.php';

use Requtize\Atline\Engine;
use Requtize\Atline\Environment;

$engine = new Engine(__DIR__.'/runtime/Cache', new MyEnvironment());
$engine->setCached(false);
$engine->setDefinitionResolver(new MyDefinitionResolver());
$engine->setDefaultExtends('base');

echo $engine->render('index', [
    'data' => '<p>This is <span style="color:red">collored</span>, <strong>stronged</strong> and <sup>sup</sup> text in paragraph.</p>'
]);
