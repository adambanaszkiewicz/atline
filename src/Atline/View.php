<?php
/**
 * This file is part of the Atline templating system package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Copyright (c) 2015 - 2016 by Adam Banaszkiewicz
 *
 * @license   MIT License
 * @copyright Copyright (c) 2015 - 2016, Adam Banaszkiewicz
 * @link      https://github.com/requtize/atline
 */

namespace Requtize\Atline;

/**
 * @author Adam Banaszkiewicz https://github.com/requtize
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
  private $sections = [];

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
    $sections = $this->getSections();

    if(isset($sections[$name]) === false)
    {
      return false;
    }

    $name = $sections[$name];

    $this->{$name}();
  }

  public function getSections()
  {
    return [];
  }

  public function getFilepath()
  {
    return null;
  }

  public function getParentFilepath()
  {
    return null;
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
