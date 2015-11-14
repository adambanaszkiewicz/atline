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
        ?><p>View: index.tpl</p>
<hr />
<h3>foreach</h3>
<?php
$array = [
    'index' => 'value',
    'price' => 12.547,
    'value indexed numericaly 1',
    'value indexed numericaly 2',
    'boolean' => true
];
var_dump($array);
?>
<?php foreach($array as $key => $item) { ?>
    <div><?= $env->filter('safe', $key); ?> => <?= $env->filter('safe', $item); ?></div>
<?php } ?>
<hr />


<h3>loop</h3>
<?php foreach($array as $key => $item) { ?>
    <div><?= $env->filter('safe', $key); ?> => <?= $env->filter('safe', $item); ?></div>
<?php } ?>
<hr />

<h3>for</h3>
<?php for($i = 0; $i < 10; $i++) { ?>
    <div>Iteracja: <?= $env->filter('safe', $i); ?></div>
<?php } ?>
<hr />


<h3>while</h3>
<?php $i = 0; $this->appendData([$i => 0]); ?>
<?php while($i < 10) { ?>
    <div>Iteracja: <?= $env->filter('safe', $i); ?></div>
    <?php $i++ ;?>
<?php } ?>
<?php
    }
}