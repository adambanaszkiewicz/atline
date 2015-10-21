<?php

include '../load.php';

use Atline\Atline\Engine;
use Atline\Atline\Environment;

$engine = new Engine(__DIR__.'/runtime/Cache', new Environment());
$engine->setCached(false);
$engine->setDefinitionResolver(new MyDefinitionResolver());
$engine->setDefaultExtends('base');
$engine->setDefaultData([
  'globalData' => [
    'global' => 'data',
    'array' => '$engine'
  ]
]);

echo $engine->render('index', [
  'viewData' => [
    'view' => 'data',
    'array' => 'render'
  ]
]);
