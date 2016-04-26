<?php

include '../load.php';

use Requtize\Atline\Engine;
use Requtize\Atline\Environment;

$engine = new Engine(__DIR__.'/runtime/Cache', new Environment());
$engine->setCached(false);
$engine->setDefinitionResolver(new MyDefinitionResolver());
$engine->setDefaultExtends('base1');

echo $engine->render('index');
