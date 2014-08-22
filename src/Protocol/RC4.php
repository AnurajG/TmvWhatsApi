<?php

namespace Tmv\WhatsApi\Protocol;

class RC4
{
    /**
     * @var array
     */
    protected $s;
    /**
     * @var int
     */
    protected $i;
    /**
     * @var int
     */
    protected $j;

    /**
     * @param string  $key
     * @param integer $drop
     */
    public function __construct($key, $drop)
    {
        $this->s = range(0, 255);
        for ($i = 0, $j = 0; $i < 256; $i++) {
            $k = ord($key{$i % strlen($key)});
            $j = ($j + $k + $this->s[$i]) & 255;
            $this->swap($i, $j);
        }

        $this->i = 0;
        $this->j = 0;
        $this->cipher(range(0, $drop), 0, $drop);
    }

    /**
     * @param  array  $data
     * @param  int    $offset
     * @param  int    $length
     * @return string
     */
    public function cipher($data, $offset, $length)
    {
        $out = $data;
        for ($n = $length; $n > 0; $n--) {
            $this->i = ($this->i + 1) & 0xff;
            $this->j = ($this->j + $this->s[$this->i]) & 0xff;
            $this->swap($this->i, $this->j);
            $d = ord($data{$offset});
            $out[$offset] = chr($d ^ $this->s[($this->s[$this->i] + $this->s[$this->j]) & 0xff]);
            $offset++;
        }

        return $out;
    }

    /**
     * @param integer $i
     * @param integer $j
     */
    protected function swap($i, $j)
    {
        $c = $this->s[$i];
        $this->s[$i] = $this->s[$j];
        $this->s[$j] = $c;
    }
}
