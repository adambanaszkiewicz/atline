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
namespace Requtize\Atline\Event\Bridge\Symfony;

use Symfony\Component\EventDispatcher\EventDispatcher;

class SymfonyEventDispatcherBridge extends EventDispatcher implements EventDispatcherInterface
{
    protected $symfonyEventDispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->symfonyEventDispatcher = $dispatcher;
    }

    public function addListener($event, \Closure $listener)
    {
        $this->symfonyEventDispatcher->addListener($this->createEventName($event), $listener);
    }

    public function dispatch($event, array $params = [])
    {
        $event = new SymfonyEvent($event, $params);

        $results = $this->symfonyEventDispatcher->dispatch($this->createEventName($event), $event);

        return $results;
    }

    public function createEventName($event)
    {
        return 'requtize_atline.'.$this->sanitizeString($event);
    }

    public function sanitizeString($string)
    {
        return preg_replace('/[^a-z0-9\_]/i', '_', $string);
    }
}
