<?php

namespace Tmv\WhatsApi\Message\Action;

/**
 * Class ClearDirty
 * Clears the "dirty" status on your account
 *
 * @package Tmv\WhatsApi\Message\Action
 */
class ClearDirty extends AbstractAction
{

    /**
     * @var array
     */
    protected $categories = array();

    /**
     * @param array $categories
     */
    public function __construct(array $categories)
    {
        $this->setCategories($categories);
    }

    /**
     * @param  array $categories
     * @return $this
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
