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
 * @date      2015.06.04
 */
class View
{
  /**
   * Data to pass to view.
   * 
   * @var array
   */
  protected $data = [];

  /**
   * Array of sections in class (child class).
   * 
   * @var array
   */
  protected $sections = [];

  /**
   * Return array of data for this view.
   * 
   * @return array
   */
  public function allData()
  {
    return $this->data;
  }

  /**
   * Append array of data to current data for this view.
   * 
   * @param  array  $data Array of data that should be appended.
   * @return self
   */
  public function appendData(array $data)
  {
    $this->data = array_merge($this->data, $data);

    return $this;
  }

  /**
   * Calls section method.
   * 
   * @param  string $name Section name.
   * @return void
   */
  public function section($name)
  {
    if(isset($this->sections[$name]) === false)
    {
      return false;
    }

    $name = $this->sections[$name];

    $this->{$name}();
  }

  /**
   * Main method, called when render compiled View.
   * 
   * @return void
   */
  public function main()
  {
  }
}