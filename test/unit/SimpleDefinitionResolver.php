<?php

use Requtize\Atline\DefinitionResolverInterface;

class SimpleDefinitionResolver implements DefinitionResolverInterface
{
    public function resolve($definition)
    {
        return $definition;
    }
}
