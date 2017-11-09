<?php

namespace Requtize\Atline;

use Requtize\Atline\Debug\ViewParser;
use Requtize\Atline\Exception\RenderException;

class Runner
{
    protected $bufferState = false;

    public function run($filepath, $className, array $data, callable $envFactory)
    {
        $content = 'Something :)';

        try {
            set_error_handler([ $this, 'eventHandler' ]);

            include_once $filepath;

            $this->bufferStart();
            $view = new $className;
            $view->appendData($data);
            $view->appendData([ 'env' => $envFactory($view) ]);
            $view->main();
            $content = $this->bufferGet();
            $this->bufferEnd();

            restore_error_handler();
        }
        catch (\ParseError $e)
        {
            $this->catchError($e, $filepath);
        }
        catch (\Error $e)
        {
            $this->catchError($e, $filepath);
        }
        catch (\Throwable $e)
        {
            $this->catchError($e, $filepath);
        }
        catch (\Exception $e)
        {
            $this->catchError($e, $filepath);
        }

        restore_error_handler();
        $this->bufferEnd();

        return $content;
    }

    public function eventHandler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        $this->bufferEnd();

        restore_error_handler();

        throw new \ErrorException($errstr, $errno, E_ERROR, $errfile, $errline);
    }

    protected function catchError($e, $filepath)
    {
        if(in_array(realpath($e->getFile()), [ realpath($filepath), __FILE__ ], true))
        {
            $parser = new ViewParser($filepath);
            $sourceFilepath = $parser->getSourceFilepath();

            throw new RenderException($e->getMessage().' - Error during render view '.$sourceFilepath.' on line '.$parser->getSourceLine($e->getLine()).'.', $e->getCode(), $e);
        }
        // Is this error is not from the rendered view, we throw it away.
        else
        {
            throw $e;
        }
    }

    public function bufferStart()
    {
        if($this->bufferState === false)
        {
            ob_start();
            $this->bufferState = true;
        }

        return $this;
    }

    public function bufferGet()
    {
        return ob_get_contents();
    }

    public function bufferEnd()
    {
        if($this->bufferState === true)
        {
            ob_end_clean();
            $this->bufferState = false;
        }

        return $this;
    }
}
