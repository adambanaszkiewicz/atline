<?php

use Atline\View;

/**
 * View filepath: view/base.tpl
 */
class Viewa889c608dd4a70e39e296148b5edff02d7c88893691af359f4828bba06e11975 extends View
{
  protected $sections = ['main' => 'main','content' => 'section_9a0364b9e99bb480dd25e1f0284c8555'];

  /**
   * Section name: main
   */
  public function main() {
    extract($this->data);
    ?>
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <title>Test Atline</title>
  </head>
  <body>
    <?= $this->section('body.top'); ?>
    <h1>Basic</h1>
    <p>View: base.tpl</p>
    <?= $this->section('content'); ?>
  </body>
</html><?php
  }

  /**
   * Section name: content
   */
  public function section_9a0364b9e99bb480dd25e1f0284c8555() {
    extract($this->data);
     
  }
}