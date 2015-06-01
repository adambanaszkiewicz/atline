<?php
/**
 * This file is part of the Atline templating system package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Copyright (c) 2015 by Adam Banaszkiewicz
 *
 * @license   MIT License
 * @copyright Copyright (c) 2015, Adam Banaszkiewicz
 * @link      https://github.com/requtize/atline
 */

namespace Atline;

/**
 * @author    Adam Banaszkiewicz https://github.com/requtize
 * @version   0.0.1
 * @date      2015.06.01
 */
class Environment
{
  /**
   * @var Atline\Engine
   */
  protected $engine;

  /**
   * Sets Engine object, to allow rendering other view in current.
   * 
   * @param Engine $engine
   * @return self
   */
  public function setEngine(Engine $engine)
  {
    $this->engine = $engine;

    return $this;
  }

  /**
   * Allowed filters:
   *   - safe      - Removes HTML tags and replace chars by entities: < > " '
   *   - stags     - Removes only HTML tags.
   *   - upper     - Uppercase text.
   *   - lower     - Lowercase text.
   *   - ucf       - Uppercase first letter in string.
   *
   * @param  string $filter Filter name/
   * @param  mixed  $input  Value to filtering.
   * @return mixed  Filtered value.
   */
  public function filter($filter, $input)
  {
    switch($filter)
    {
      case 'stags': $input = strip_tags($input); break;
      case 'upper': $input = mb_strtoupper($input); break;
      case 'lower': $input = mb_strtolower($input); break;
      case 'ucf':   $input = $this->mb_ucfirst($input);    break;
      case 'raw':   null; break;
      // Equivalent to = safe
      default: $input = htmlspecialchars(strip_tags($input), ENT_QUOTES);
    }

    return $input;
  }

  /**
   * Render view.
   *
   * @param  string $definition View definition.
   * @param  array  $data       Array of Data to pass into rendered view.
   * @return string             Rendered View.
   */
  public function render($definition, array $data = [])
  {
    return $this->engine->render($definition, $data);
  }

  protected function mb_ucfirst($string, $encoding = 'utf8')
  {
    $strlen     = mb_strlen($string, $encoding);
    $firstChar  = mb_substr($string, 0, 1, $encoding);
    $then       = mb_substr($string, 1, $strlen - 1, $encoding);
    return mb_strtoupper($firstChar, $encoding) . $then;
  }
}