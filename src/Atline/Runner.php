<?php

namespace Requtize\Atline;

use Requtize\Atline\Debug\ViewParser;
use Requtize\Atline\Exception\RenderException;

class Runner
{
    protected $bufferState = false;
    protected $bufferLevel = 0;

    public function run($filepath, $className, array $data, callable $envFactory)
    {
        $content = '';

        try {
            set_error_handler([ $this, 'eventHandler' ]);

            include_once $filepath;

            $this->bufferStart();
            $view = new $className;
            $view->appendData($data);
            $view->appendData([ 'env' => $envFactory($view) ]);
            $view->main();
            $content = $this->bufferGetEnd();
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
        restore_error_handler();

        throw new \ErrorException($errstr, $errno, E_ERROR, $errfile, $errline);
    }

    protected function catchError($e, $filepath)
    {
        $this->bufferEnd();
        
        $files = (new ViewParser($filepath))->getFilenamesHierarchy();
        $files[] = realpath($filepath);
        $files[] = __FILE__;

        if(in_array(realpath($e->getFile()), $files, true))
        {
            $parser = new ViewParser($e->getFile());

            throw new RenderException($e->getMessage().' - Error during render view '.$parser->getSourceFilepath().' on line '.$parser->getSourceLine($e->getLine()).'.', $e->getCode(), $e);
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
            $this->bufferLevel = ob_get_level();
            $this->bufferState = true;
            ob_start();
        }

        return $this;
    }

    public function bufferGetEnd()
    {
        $content = [];

        if($this->bufferState === true)
        {
            /**
             * Close all opened buffers during render view
             * until is level when we start rendering.
             */
            while($this->bufferLevel < ob_get_level())
            {
                $content[] = ob_get_contents();
                ob_end_clean();
            }

            $this->bufferState = false;
        }

        /**
         * When we have multiple Buffers turned on and we want to close them and get
         * its contents, there will be closed in reversed order (like recurrency), so at
         * the end we need to repair this ordering.
         */
        return implode(array_reverse($content));
    }

    public function bufferEnd()
    {
        if($this->bufferState === true)
        {
            /**
             * Close all opened buffers during render view
             * until is level when we start rendering.
             */
            while($this->bufferLevel < ob_get_level())
                ob_end_clean();

            $this->bufferState = false;
        }

        return $this;
    }
}
