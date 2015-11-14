<?php

use Atline\Atline\View;

/**
 * View filepath: view/view1.tpl
 */
class View1a314683c46360f9d76badcf3255aa1c19510cb636eb4f9dfee21626363f130a extends View
{
    protected $sections = ['main' => 'main'];

    /**
     * Section name: main
     */
    public function main() {
        extract($this->data);
        ?><p>View: view1.tpl</p>
<?php
    }
}