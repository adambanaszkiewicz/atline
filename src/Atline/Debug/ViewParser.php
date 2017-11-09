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
                if(preg_match('/\{src\-line\:(\d)\}/i', $line, $matches) !== false)
                {
                    return $matches[1];
                }
            }
        }
    }
}
