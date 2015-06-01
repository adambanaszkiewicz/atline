<?php

use Atline\View;

/**
 * View filepath: view/view.tpl
 */
class Viewbe23c460f4ad931a59a8e2df349e0f21fe0117a4cfb0933ef546631b5d31a1b6 extends View
{
  protected $sections = ['main' => 'main'];

  /**
   * Section name: main
   */
  public function main() {
    extract($this->data);
    ?><p>View: view.tpl</p><?php
  }
}