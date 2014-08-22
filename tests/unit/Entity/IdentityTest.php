<?php

namespace Tmv\WhatsApi\Entity;

use Mockery as m;

class IdentityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Identity
     */
    protected $object;

    public function setUp()
    {
        $this->object = new Identity();
    }

    protected function tearDown()
    {
        m::close();
    }

    public function testSettersAndGettersMethods()
    {
        $data = array(
            'nickname' => 'my-nickname',
            'password' => 'my-password',
            'token' => 'my-token',
        );

        $phoneMock = m::mock(__NAMESPACE__.'\\Phone');

        $this->object->setNickname($data['nickname']);
        $this->object->setPassword($data['password']);
        $this->object->setToken($data['token']);
        $this->object->setPhone($phoneMock);

        $this->assertEquals($data['nickname'], $this->object->getNickname());
        $this->assertEquals($data['password'], $this->object->getPassword());
        $this->assertEquals($data['token'], $this->object->getToken());
        $this->assertEquals($phoneMock, $this->object->getPhone());
    }

    public function testGetIdentityString()
    {
        $this->object->setToken('identity20identity20');
        $ret = $this->object->getIdentityString();
        $this->assertEquals('identity20identity20', $ret);

        $this->object->setToken('test-token');

        $ret = $this->object->getIdentityString();
        $this->assertEquals('%28%e2%bc%a8%9d%8c%60%c5%11z%5b%9efcq%9e%c2%c9%90%3c', $ret);
    }
}
