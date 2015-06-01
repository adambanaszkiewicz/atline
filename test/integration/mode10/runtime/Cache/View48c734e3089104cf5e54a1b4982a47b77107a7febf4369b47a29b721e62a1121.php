<?php

require_once "Viewa889c608dd4a70e39e296148b5edff02d7c88893691af359f4828bba06e11975.php";

use Atline\View;

/**
 * View filepath: view/index.tpl
 */
class View48c734e3089104cf5e54a1b4982a47b77107a7febf4369b47a29b721e62a1121 extends Viewa889c608dd4a70e39e296148b5edff02d7c88893691af359f4828bba06e11975
{
  protected $sections = ['content' => 'section_9a0364b9e99bb480dd25e1f0284c8555'];

  /**
   * Section name: content
   */
  public function section_9a0364b9e99bb480dd25e1f0284c8555() {
    extract($this->data);
    ?><h3>Function: t</h3>
<?= $env->filter('safe', $env->t('translate')); ?>

<h3>Function: t (raw)</h3>
<?= $env->t('translate'); ?>

<h3>Filter: none</h3>
<?= $env->filter('safe', $data); ?>

<h3>Filter: raw</h3>
<?= $data;  
  }
}