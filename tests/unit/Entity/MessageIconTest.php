<?php

namespace Tmv\WhatsApi\Entity;

function file_exists()
{
    return true;
}

function fopen()
{
    return true;
}

function is_resource()
{
    return true;
}

class MessageIconTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $icon = new MessageIcon('file.jpg');
        $this->assertInstanceOf('Tmv\WhatsApi\Entity\MessageIcon', $icon);
    }
}
