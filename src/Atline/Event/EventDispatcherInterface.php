<?php
/**
 * This file is part of the Atline templating system package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Copyright (c) 2015 - 2017 by Adam Banaszkiewicz
 *
 * @license   MIT License
 * @copyright Copyright (c) 2015 - 2017, Adam Banaszkiewicz
 * @link      https://github.com/requtize/atline
 */
namespace Requtize\Atline\Event;

interface EventDispatcherInterface
{
    public function addListener($event, \Closure $listener);

    public function removeListeners($event);

    public function dispatch($event, array $params = []);
}
