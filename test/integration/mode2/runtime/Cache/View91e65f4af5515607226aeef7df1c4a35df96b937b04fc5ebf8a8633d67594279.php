<?php

use Atline\View;

/**
 * View filepath: view/base1.tpl
 */
class View91e65f4af5515607226aeef7df1c4a35df96b937b04fc5ebf8a8633d67594279 extends View
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
    <?= $this->section('head.bottom'); ?>
  </head>
  <body>
    <?= $this->section('body.top'); ?>
    <h1>Basic</h1>
    <p>View: base1.tpl</p>
    <div id="wrapper">
      <div id="page-wrapper">
        <?= $this->section('content'); ?>
      </div>
    </div>

    <?= $this->section('body.bottom'); ?>
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