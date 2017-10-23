<?php

class TestCase extends PHPUnit_Framework_TestCase
{
    protected function createTemporaryViewFile($content, $name = null)
    {
        $name = $name ? $name : uniqid();

        file_put_contents(__DIR__.'/cache/'.$name, $content);

        return __DIR__.'/cache/'.$name;
    }
}
