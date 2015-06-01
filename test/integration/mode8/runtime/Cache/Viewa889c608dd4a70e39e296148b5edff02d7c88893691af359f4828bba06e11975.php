<?php

use Atline\View;

/**
 * View filepath: view/base.tpl
 */
class Viewa889c608dd4a70e39e296148b5edff02d7c88893691af359f4828bba06e11975 extends View
{
  protected $sections = ['main' => 'main','special' => 'section_0bd6506986ec42e732ffb866d33bb14e','content' => 'section_9a0364b9e99bb480dd25e1f0284c8555'];

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
    <h3>Section special</h3>
    <hr />
    <?= $this->section('special'); ?>
    <hr />
    <h3>Section parent</h3>
    <hr />
    <?= $this->section('parent'); ?>
    <hr />
    <h3>Section content</h3>
    <hr />
    <?= $this->section('content'); ?>
    <hr />
  </body>
</html><?php
  }

  /**
   * Section name: special
   */
  public function section_0bd6506986ec42e732ffb866d33bb14e() {
    extract($this->data);
    ?>
<p>SECTION 'special': base.tpl</p><?php
  }

  /**
   * Section name: content
   */
  public function section_9a0364b9e99bb480dd25e1f0284c8555() {
    extract($this->data);
     
  }
}