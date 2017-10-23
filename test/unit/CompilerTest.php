<?php

use Requtize\Atline\Compiler;

class CompilerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataCompileProvider
     */
    public function testCompile($source, $compiled)
    {
        $file = $this->createTemporaryViewFile($source);

        $compiler = new Compiler($file, false);
        $compiler->compile();

        $this->assertEquals($compiler->getPreparedContent(), $compiled);

        unlink($file);
    }

    public function dataCompileProvider()
    {
        return [
            [
                '{# Comment #}',
                ""
            ],
            [
                '{# Comment
                multiline
                ...
                ... #}',
                ""
            ],
            [
                '{{ $echo }}',
                "<?= \$env->filter('safe', \$echo); ?>"
            ],
            [
                '{{ $echo }}{{ $var }}',
                "<?= \$env->filter('safe', \$echo); ?><?= \$env->filter('safe', \$var); ?>"
            ],
            [
                '{{ strtoupper($echo) }}',
                "<?= strtoupper(\$echo); ?>"
            ],
            [
                '{{ environmentFunction($echo) }}',
                "<?= \$env->environmentFunction(\$echo); ?>"
            ],
            [
                '{{ environmentFunction($echo) }}',
                "<?= \$env->environmentFunction(\$echo); ?>"
            ],
            [
                '{{ $echo | raw }}',
                "<?= \$echo; ?>"
            ],
            [
                '{{ $echo | lower }}',
                "<?= \$env->filter('lower', \$env->filter('safe', \$echo)); ?>"
            ],
            [
                '{{ $echo | lower | stags }}',
                "<?= \$env->filter('lower', \$env->filter('stags', \$env->filter('safe', \$echo))); ?>"
            ],
            [
                "@render('section-name')",
                "<?= \$env->render('section-name', \$this->allData()); ?>"
            ],
            [
                "@render('section-name', [ 'some' => 'data' ])",
                "<?= \$env->render('section-name', array_merge(\$this->allData(), [ 'some' => 'data' ])); ?>"
            ],
            [
                "@if true\n@endif",
                "<?php if(true) { ?>\n<?php } ?>"
            ],
            [
                "@if true\n@else\n@endif",
                "<?php if(true) { ?>\n<?php } else { ?>\n<?php } ?>"
            ],
            [
                "@if true\n@elseif false\n@else\n@endif",
                "<?php if(true) { ?>\n<?php } elseif(false) { ?>\n<?php } else { ?>\n<?php } ?>"
            ],
            [
                "@foreach \$items\n@endforeach",
                "<?php foreach(\$items as \$key => \$item) { ?>\n<?php } ?>"
            ],
            [
                "@foreach \$items as \$row\n@endforeach",
                "<?php foreach(\$items as \$row) { ?>\n<?php } ?>"
            ],
            [
                "@foreach \$items as \$key => \$row\n@endforeach",
                "<?php foreach(\$items as \$key => \$row) { ?>\n<?php } ?>"
            ],
            [
                "@loop \$items\n@endloop",
                "<?php foreach(\$items as \$key => \$item) { ?>\n<?php } ?>"
            ],
            [
                "@loop \$items as \$row\n@endloop",
                "<?php foreach(\$items as \$row) { ?>\n<?php } ?>"
            ],
            [
                "@loop \$items as \$key => \$row\n@endloop",
                "<?php foreach(\$items as \$key => \$row) { ?>\n<?php } ?>"
            ],
            [
                "@for \$i = 0; \$i < 10; \$i++\n@endfor",
                "<?php for(\$i = 0; \$i < 10; \$i++) { ?>\n<?php } ?>"
            ],
            [
                "@for ; \$i < 10; \$i++\n@endfor",
                "<?php for(; \$i < 10; \$i++) { ?>\n<?php } ?>"
            ],
            [
                "@for ; \$i < 10;\n@endfor",
                "<?php for(; \$i < 10;) { ?>\n<?php } ?>"
            ],
            [
                "@while \$i == 10\n@endwhile",
                "<?php while(\$i == 10) { ?>\n<?php } ?>"
            ],
            [
                "@set \$variable 10",
                "<?php \$variable = 10; \$this->appendData([\$variable => 10]); ?>"
            ],
            [
                "@parent",
                "<?= parent::{explode('::', __METHOD__)[1]}(); ?>"
            ],
            [
                "@show('section-name.withSome1234')",
                "<?= \$this->section('section-name.withSome1234'); ?>"
            ],
        ];
    }

    public function testExtendedView()
    {
        $source = <<<ATLINE
@extends('parentViewName')

Rest of view...
ATLINE;
        $file = $this->createTemporaryViewFile($source);

        $compiler = new Compiler($file, false);

        $this->assertNull($compiler->getExtendedDefinition());
        $compiler->compile();
        $this->assertEquals($compiler->getExtendedDefinition(), 'parentViewName');


        $compiler = new Compiler($file, false);
        $compiler->setExtendedDefinition('predefinedDefinition');

        $this->assertEquals($compiler->getExtendedDefinition(), 'predefinedDefinition');
        $compiler->compile();
        $this->assertEquals($compiler->getExtendedDefinition(), 'parentViewName');
        $compiler->setExtendedDefinition(null);
        $this->assertNull($compiler->getExtendedDefinition());

        unlink($file);

        $source = <<<ATLINE
@no-extends

Rest of view...
ATLINE;
        $file = $this->createTemporaryViewFile($source);

        $compiler = new Compiler($file, false);
        $compiler->setExtendedDefinition('predefinedDefinition');

        $this->assertEquals($compiler->getExtendedDefinition(), 'predefinedDefinition');
        $compiler->compile();
        $this->assertNull($compiler->getExtendedDefinition());

        unlink($file);
    }

    public function testSections()
    {
        $source = <<<ATLINE
@section('main')
main
@endsection
@section('sub')
sub
@endsection
@section('section2')
section2
@endsection
ATLINE;
        $file = $this->createTemporaryViewFile($source);

        $compiler = new Compiler($file, false);
        $this->assertEmpty($compiler->getSections());

        $compiler->compile($file);

        $this->assertEquals(count($compiler->getSections()), 3);
        $this->assertEquals($compiler->getSections(), [
            [
                'name' => 'main',
                'full' => "@section('main')main@endsection",
                'content' => "\nmain"
            ],
            [
                'name' => 'sub',
                'full' => "@section('sub')sub@endsection",
                'content' => "\nsub"
            ],
            [
                'name' => 'section2',
                'full' => "@section('section2')section2@endsection",
                'content' => "\nsection2"
            ]
        ]);

        unlink($file);
    }

    protected function createTemporaryViewFile($content, $name = null)
    {
        $name = $name ? $name : uniqid();

        file_put_contents(__DIR__.'/cache/'.$name, $content);

        return __DIR__.'/cache/'.$name;
    }
}
