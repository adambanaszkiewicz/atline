<?php
{{head}}
use Requtize\Atline\View;

/**
 * __ATLINE_RENDER_METADATA__
 * source-filepath: {{filepath}}
 * __ATLINE_RENDER_METADATA__
 */
class {{classname}} extends {{extends}}
{
    private $sections = [];

    public function getSections()
    {
        return array_merge(parent::getSections(), $this->sections);
    }

    public function getFilepath()
    {
        return '{{filepath}}';
    }

    public function getParentFilepath()
    {
        return parent::getFilepath();
    }

    public function main()
    {
        {{content}}
    }
}
