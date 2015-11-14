<?php

use Atline\Atline\View;

/**
 * View filepath: view/index.tpl
 */
class View48c734e3089104cf5e54a1b4982a47b77107a7febf4369b47a29b721e62a1121 extends View
{
    protected $sections = ['main' => 'main'];

    /**
     * Section name: main
     */
    public function main() {
        extract($this->data);
        ?><h2>START</h2>
<p>View: index.tpl</p>

<p>-------------------------view.tpl-------------------------</p>
<?= $env->render('view1', $this->allData()); ?>
<?= $env->render('view2', $this->allData()); ?>
<p>-------------------------view.tpl-------------------------</p>

<h2>END</h2><?php
    }
}