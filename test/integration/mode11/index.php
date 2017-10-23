<?php

include '../load.php';

use Requtize\Atline\Engine;
use Requtize\Atline\Environment;

$engine = new Engine(__DIR__.'/runtime/Cache', function () {
    return new Environment;
});
$engine->setCached(false);
$engine->setDefinitionResolver(new MyDefinitionResolver());
$engine->setDefaultExtends('base');

echo $engine->render('index');
