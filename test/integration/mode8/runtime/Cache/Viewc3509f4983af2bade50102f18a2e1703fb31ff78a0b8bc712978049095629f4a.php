<?php

require_once "Viewa889c608dd4a70e39e296148b5edff02d7c88893691af359f4828bba06e11975.php";

use Atline\View;

/**
 * View filepath: view/parent.tpl
 */
class Viewc3509f4983af2bade50102f18a2e1703fb31ff78a0b8bc712978049095629f4a extends Viewa889c608dd4a70e39e296148b5edff02d7c88893691af359f4828bba06e11975
{
  protected $sections = ['special' => 'section_0bd6506986ec42e732ffb866d33bb14e','parent' => 'section_d0e45878043844ffc41aac437e86b602','content' => 'section_9a0364b9e99bb480dd25e1f0284c8555'];

  /**
   * Section name: special
   */
  public function section_0bd6506986ec42e732ffb866d33bb14e() {
    extract($this->data);
    ?>
<p>SECTION 'special': parent.tpl</p><?php
  }

  /**
   * Section name: parent
   */
  public function section_d0e45878043844ffc41aac437e86b602() {
    extract($this->data);
    ?>
<p>SECTION 'parent': parent.tpl</p><?php
  }

  /**
   * Section name: content
   */
  public function section_9a0364b9e99bb480dd25e1f0284c8555() {
    extract($this->data);
    ?>

<p>SECTION 'content': parent.tpl</p><?php
  }
}