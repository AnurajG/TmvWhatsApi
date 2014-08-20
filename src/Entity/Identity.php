<?php

namespace Tmv\WhatsApi\Entity;

class Identity
{
    /**
     * @var string
     */
    protected $nickname;
    /**
     * @var string
     */
    protected $token;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var Phone
     */
    protected $phone;
    /**
     * @var string
     */
    protected $identityString;

    /**
     * @param  string $nickname
     * @return $this
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param  string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param  string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->identityString = null;
        $this->token = $token;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param  Phone $phone
     * @return $this
     */
    public function setPhone(Phone $phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return \Tmv\WhatsApi\Entity\Phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getIdentityString()
    {
        if (!$this->identityString) {
            if (!$this->checkIdentity($this->getToken())) {
                $this->identityString = strtolower(urlencode(sha1($this->getToken(), true)));
            } else {
                $this->identityString = $this->getToken();
            }
        }

        return $this->identityString;
    }

    /**
     * Check validity of an identity
     *
     * @param  string $identity
     * @return bool
     */
    protected function checkIdentity($identity)
    {
        return (strlen(urldecode($identity)) == 20);
    }
}
