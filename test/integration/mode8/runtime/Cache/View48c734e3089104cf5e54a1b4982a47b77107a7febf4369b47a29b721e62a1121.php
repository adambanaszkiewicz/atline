<?php

require_once "Viewc3509f4983af2bade50102f18a2e1703fb31ff78a0b8bc712978049095629f4a.php";

use Atline\View;

/**
 * View filepath: view/index.tpl
 */
class View48c734e3089104cf5e54a1b4982a47b77107a7febf4369b47a29b721e62a1121 extends Viewc3509f4983af2bade50102f18a2e1703fb31ff78a0b8bc712978049095629f4a
{
  protected $sections = ['special' => 'section_0bd6506986ec42e732ffb866d33bb14e','parent' => 'section_d0e45878043844ffc41aac437e86b602','content' => 'section_9a0364b9e99bb480dd25e1f0284c8555'];

  /**
   * Section name: special
   */
  public function section_0bd6506986ec42e732ffb866d33bb14e() {
    extract($this->data);
    ?>
<p>SECTION 'special': index.tpl</p><?php
  }

  /**
   * Section name: parent
   */
  public function section_d0e45878043844ffc41aac437e86b602() {
    extract($this->data);
    ?>
<?= parent::{explode('::', __METHOD__)[1]}(); ?>
<p>SECTION 'parent': index.tpl</p><?php
  }

  /**
   * Section name: content
   */
  public function section_9a0364b9e99bb480dd25e1f0284c8555() {
    extract($this->data);
    ?>

<p>SECTION 'content': index.tpl</p><?php
  }
}