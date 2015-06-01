<?php

use Atline\View;

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
<?php $switch = isset($_GET['switch']) ? $_GET['switch'] : 1; $this->appendData([$switch => isset($_GET['switch']) ? $_GET['switch'] : 1]); ?>

<?php if($switch == 1) { ?>
  <p>Podano wartosc $switch == 1 (if)</p>
<?php } elseif($switch == 2) { ?>
  <p>Podano wartosc $switch == 2 (elseif)</p>
<?php } else { ?>
  <p>Podano inna wartosc niz 1 lub 2 (else)</p>
<?php } ?>

<a href="?switch=1">?switch=1</a> | <a href="?switch=2">?switch=2</a> | <a href="?switch=3">?switch=3</a><?php
  }
}