<?php

use Requtize\Atline\Runner;
use Requtize\Atline\Exception\RenderException;

class RunnerTest extends TestCase
{
    public function testValidRun()
    {
        list($filepath, $classname) = $this->createViewClass("echo ':)';");

        $result = (new Runner)->run($filepath, $classname, [], function(){});

        $this->assertSame($result, ':)');

        unlink($filepath);
    }

    public function testParseError()
    {
        $this->assertParseErrorMessageBeginFrom_singular(
            "// {src-line:1}\necho ':);",
            "syntax error, unexpected '':);' (T_ENCAPSED_AND_WHITESPACE) -",
            1
        );
        $this->assertParseErrorMessageBeginFrom_singular(
            "// {src-line:1}\n'",
            "syntax error, unexpected quoted-string and whitespace (T_ENCAPSED_AND_WHITESPACE) -",
            1
        );
        $this->assertParseErrorMessageBeginFrom_singular(
            "// {src-line:1}\necho '';\n// {src-line:2}\ninclude 'file';",
            "include(file): failed to open stream: No such file or directory -",
            2
        );
        $this->assertParseErrorMessageBeginFrom_singular(
            "// {src-line:1}\necho '';\n// {src-line:2}\n\$o = new \SomeClass;\n// {src-line:3}\necho '';",
            "Class 'SomeClass' not found -",
            2
        );
        $this->assertParseErrorMessageBeginFrom_singular(
            "// {src-line:1}\necho '';\n// {src-line:2}\n\$o = [\n'asd',\nnew stdClass,\n1 + asd\n];\n// {src-line:3}\necho '';",
            "Use of undefined constant asd - assumed 'asd' -",
            'undefined'
        );
        $this->assertParseErrorMessageBeginFrom_singular(
            "// {src-line:1}\necho '';\n// {src-line:2}\n\$o = [\n// {src-line:3}\n'asd',\n// {src-line:4}\nnew stdClass,\n// {src-line:5}\n1 + asd\n// {src-line:6}\n];\n// {src-line:7}\necho '';",
            "Use of undefined constant asd - assumed 'asd' -",
            5
        );
        $this->assertParseErrorMessageBeginFrom_singular(
            "// {src-line:1}\necho '';\n// {src-line:2}\necho \$variable['index'];",
            "Undefined variable: variable -",
            2
        );
        $this->assertParseErrorMessageBeginFrom_singular(
            "// {src-line:1}\necho '';\n// {src-line:2}\n\$variable = [];\n// {src-line:3}\necho \$variable['index'];",
            "Undefined index: index -",
            3
        );
    }

    public function testParseErrorBigLineNumber()
    {
        $this->assertParseErrorMessageBeginFrom_singular(
            "// {src-line:1234567890}\necho ':);",
            "syntax error, unexpected quoted-string and whitespace (T_ENCAPSED_AND_WHITESPACE) -",
            1234567890
        );
    }

    public function testParseErrorLineNumberInSeparatePhpStatement()
    {
        $this->assertParseErrorMessageBeginFrom_singular(
            "?>\nsomethind\n<?php // {src-line:2354235} ?>\n<?= ' ?>",
            "syntax error, unexpected quoted-string and whitespace (T_ENCAPSED_AND_WHITESPACE) -",
            2354235
        );
    }

    public function testParseErrorInParentClass()
    {
        $this->assertParseErrorMessageBeginFrom_extended_inMainview(
            "// {src-line:1}\n'",
            "",
            "syntax error, unexpected quoted-string and whitespace (T_ENCAPSED_AND_WHITESPACE) -",
            1
        );
    }

    /*public function testParseErrorOutputBufferStartedNotClosed()
    {
        
    }*/

    public function testOutputBufferStartedNotClosed()
    {
        list($filepath, $classname) = $this->createViewClass("echo 1;ob_start();echo 2;\nob_start();echo 3;");

        $result = (new Runner)->run($filepath, $classname, [], function(){});

        $this->assertSame('123', $result);
    }

    public function assertParseErrorMessageBeginFrom_singular($content, $messageBeginning, $line)
    {
        list($filepath, $classname) = $this->createViewClass($content);

        $messageEnd = "{$filepath} on line {$line}.";

        try {
            (new Runner)->run($filepath, $classname, [], function(){});
        } catch (RenderException $e) {
            $this->assertSame($messageBeginning, substr($e->getMessage(), 0, strlen($messageBeginning)));
            $this->assertSame($messageEnd, substr($e->getMessage(), -strlen($messageEnd)));
        }

        unlink($filepath);
    }

    public function assertParseErrorMessageBeginFrom_extended_inMainview($contentMain, $contentChild, $messageBeginning, $line)
    {
        list($mainFilepath, $mainClassname) = $this->createViewClass($contentMain);
        list($childFilepath, $childClassname) = $this->createViewClass($contentChild, [
            '{{extends}}' => $mainClassname,
            '{{head}}' => "require_once '".$mainFilepath."';"
        ]);

        $messageEnd = "{$mainFilepath} on line {$line}.";

        try {
            (new Runner)->run($childFilepath, $childClassname, [], function(){});
        } catch (RenderException $e) {
            $this->assertSame($messageBeginning, substr($e->getMessage(), 0, strlen($messageBeginning)));
            $this->assertSame($messageEnd, substr($e->getMessage(), -strlen($messageEnd)));
        }

        unlink($mainFilepath);
        unlink($childFilepath);
    }

    public function createViewClass($content, array $placeholders = [])
    {
        $pattern = file_get_contents(__DIR__.'/resources/runnertest/ValidClass.php');
        $classNameUnique = 'View'.str_replace('.', '', uniqid('', true));
        $filepath = __DIR__.'/cache/'.$classNameUnique.'.php';

        file_put_contents($filepath, '');

        $filepath = realpath($filepath);

        $placeholders = array_merge([
            '{{filepath}}'  => $filepath,
            '{{classname}}' => $classNameUnique,
            '{{content}}'   => $content,
            '{{extends}}'   => 'View',
            '{{head}}'      => ''
        ], $placeholders);

        $pattern = str_replace(array_keys($placeholders), array_values($placeholders), $pattern);

        file_put_contents($filepath, $pattern);

        return [
            $filepath,
            $classNameUnique
        ];
    }
}
