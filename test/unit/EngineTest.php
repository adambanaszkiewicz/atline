<?php

use Requtize\Atline\Engine;
use Requtize\Atline\Environment;

class EngineTest extends TestCase
{
    public function testEventDispatcher()
    {
        $source = <<<ATLINE

ATLINE;

        $file = $this->createTemporaryViewFile($source);

        $engine = new Engine(__DIR__.'/cache', function () {
            return new Environment;
        });
        $engine->setDefinitionResolver(new SimpleDefinitionResolver);

        $eventsCalls = [
            'create-env' => 0,
            'render.compile.before' => 0,
            'render.compile_view.before' => 0,
            'render.compile_view.after' => 0,
            'render.compile.after' => 0,
            'not-existent-event' => 0
        ];

        $dispatcher = $engine->getEventDispatcher();
        $dispatcher->addListener('environment.create', function () use (& $eventsCalls) {
            $eventsCalls['create-env']++;
        });
        $dispatcher->addListener('render.compile.before', function () use (& $eventsCalls) {
            $eventsCalls['render.compile.before']++;
        });
        $dispatcher->addListener('render.compile_view.before', function () use (& $eventsCalls) {
            $eventsCalls['render.compile_view.before']++;
        });
        $dispatcher->addListener('render.compile_view.after', function () use (& $eventsCalls) {
            $eventsCalls['render.compile_view.after']++;
        });
        $dispatcher->addListener('render.compile.after', function () use (& $eventsCalls) {
            $eventsCalls['render.compile.after']++;
        });

        $engine->render($file);

        $this->assertEquals($eventsCalls['create-env'], 1);
        $this->assertEquals($eventsCalls['render.compile.before'], 1);
        $this->assertEquals($eventsCalls['render.compile_view.before'], 1);
        $this->assertEquals($eventsCalls['render.compile_view.after'], 1);
        $this->assertEquals($eventsCalls['render.compile.after'], 1);
        $this->assertEquals($eventsCalls['not-existent-event'], 0);

        unlink($file);
    }
}
