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

use Requtize\QueryBuilder\Connection;
use Requtize\QueryBuilder\Parameters;

Class EventDispatcher implements EventDispatcherInterface
{
    protected $listeners = [];

    public function addListener($event, \Closure $listener)
    {
        $this->listeners[$event][] = $listener;

        return $this;
    }

    public function removeListeners($event)
    {
        unset($this->listeners[$event]);

        return $this;
    }

    public function dispatch($event, array $params = [])
    {
        if(isset($this->listeners[$event]))
        {
            foreach($this->listeners[$event] as $listener)
            {
                $result = call_user_func_array($listener, $params);

                if($result !== null)
                {
                    return $result;
                }
            }
        }
    }
}
