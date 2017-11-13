<?php

namespace Requtize\Atline\Debug;

class ViewParser
{
    protected $filepath;
    protected $content;

    public function __construct($filepath)
    {
        $this->filepath = $filepath;
        $this->content  = file_get_contents($filepath);
    }

    public function getFilepath()
    {
        return $this->filepath;
    }

    public function getClassname()
    {
        preg_match('/class\s([a-z0-9]+)/i', $this->content, $matches);

        return isset($matches[1]) ? $matches[1] : null;
    }

    public function getParentClassFilepath()
    {
        preg_match('/extends\s([a-z0-9]+)/i', $this->content, $matches);

        return isset($matches[1]) ? $matches[1] : null;
    }

    public function getParentClassParser()
    {
        $parent = $this->getParentClassFilepath();

        if($parent && $parent != 'View')
            return new self(dirname($this->filepath).'/'.$parent.'.php');
        else
            return null;
    }

    public function getClassnameHierarchy(array & $parents = [])
    {
        $parser = $this->getParentClassParser();

        $parents[] = $this->getClassname();

        if($parser)
        {
            $parser->getClassnameHierarchy($parents);
        }

        return $parents;
    }

    public function getFilenamesHierarchy(array & $parents = [])
    {
        $parser = $this->getParentClassParser();

        $parents[] = realpath($this->getFilepath());

        if($parser)
        {
            $parser->getFilenamesHierarchy($parents);
        }

        return $parents;
    }

    public function getSourceFilepath()
    {
        preg_match('/__ATLINE_RENDER_METADATA__(.+?)__ATLINE_RENDER_METADATA__/ims', $this->content, $matches);

        $lines = array_map(function ($line) {
            return ltrim($line, " \n\t\r\0\x0B*");
        }, explode("\n", $matches[1]));

        $metadata = [];

        foreach($lines as $line)
        {
            if($line == '')
                continue;

            list($key, $val) = explode(':', $line, 2);

            $metadata[trim($key)] = trim($val);
        }

        return $metadata['source-filepath'];
    }

    public function getSourceLine($renderedLine)
    {
        $lines = preg_split('/\n|\r/i', $this->content);

        $commentLine = $renderedLine - 1;

        foreach($lines as $lineno => $line)
        {
            if(($lineno + 1) == $commentLine)
            {
                if(preg_match('/\{src\-line\:(\d+)\}/i', $line, $matches) !== false)
                {
                    return isset($matches[1]) ? $matches[1] : 'undefined';
                }
            }
        }
    }
}
