<?php

use Atline\Atline\View;

/**
 * View filepath: view/view2.tpl
 */
class View20af5828261363fd9e41992782887182cb95195e2f5dc5a2d3f4c364df84351a extends View
{
    protected $sections = ['main' => 'main'];

    /**
     * Section name: main
     */
    public function main() {
        extract($this->data);
        ?><p>View: view2.tpl</p>
<?php
    }
}