<?php

namespace Tmv\WhatsApi\Entity;

use Mockery as m;

class IdentityTest extends \PHPUnit_Framework_TestCase
{

    protected function tearDown()
    {
        m::close();
    }

    public function testSettersAndGettersMethods()
    {
        $data = [
            'nickname' => 'my-nickname',
            'password' => 'my-password',
            'token' => 'my-token',
        ];

        $phoneMock = m::mock(__NAMESPACE__.'\\Phone');

        $identity = new Identity($phoneMock);
        $identity->setNickname($data['nickname']);
        $identity->setPassword($data['password']);
        $identity->setIdentityToken($data['token']);
        $identity->setPhone($phoneMock);

        $this->assertEquals($data['nickname'], $identity->getNickname());
        $this->assertEquals($data['password'], $identity->getPassword());
        $this->assertEquals($data['token'], $identity->getIdentityToken());
        $this->assertEquals($phoneMock, $identity->getPhone());
    }

    public function testCreateJID()
    {
        $number = '393921234567@s.whatsapp.net';
        $ret = Identity::createJID($number);
        $this->assertEquals($number, $ret);

        $number = '393921234567';
        $ret = Identity::createJID($number);
        $this->assertEquals($number.'@s.whatsapp.net', $ret);

        // test group
        $number = '393921234567-1425645';
        $ret = Identity::createJID($number);
        $this->assertEquals($number.'@g.us', $ret);
    }

    public function testParseJID()
    {
        $number = '393921234567';
        $ret = Identity::parseJID($number);
        $this->assertEquals($number, $ret);
    }
}
