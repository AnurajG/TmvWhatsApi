<?php

namespace Tmv\WhatsApi\Message\Action;

use RuntimeException;

class NodeFactory
{
    /**
     * Factory Map
     *
     * @var array
     */
    protected $factoryMap = array(
        'Tmv\\WhatsApi\\Message\\Action\\MessageText' => 'Tmv\\WhatsApi\\Message\\Action\\MessageTextFactory',
        'Tmv\\WhatsApi\\Message\\Action\\ChatState' => 'Tmv\\WhatsApi\\Message\\Action\\ChatStateFactory',
        'Tmv\\WhatsApi\\Message\\Action\\ClearDirty' => 'Tmv\\WhatsApi\\Message\\Action\\ClearDirtyFactory',
        'Tmv\\WhatsApi\\Message\\Action\\Presence' => 'Tmv\\WhatsApi\\Message\\Action\\PresenceFactory',
        'Tmv\\WhatsApi\\Message\\Action\\Receipt' => 'Tmv\\WhatsApi\\Message\\Action\\ReceiptFactory',
    );
    /**
     * Already instanced factories
     *
     * @var array
     */
    protected $instances = array();

    /**
     * @param  ActionInterface                          $action
     * @return \Tmv\WhatsApi\Message\Node\NodeInterface
     * @throws RuntimeException
     */
    public function createNode(ActionInterface $action)
    {
        $factory = $this->getFactoryForAction($action);

        return $factory->createNode($action);
    }

    /**
     * @param  ActionInterface            $action
     * @return FactoryInterface
     */
    public function getFactoryForAction(ActionInterface $action)
    {
        $key = get_class($action);
        if (isset($this->instances[$key])) {
            return $this->instances[$key];
        }

        return $this->createFactory($key);
    }

    /**
     * @param  string                     $key
     * @return FactoryInterface
     * @throws RuntimeException
     */
    protected function createFactory($key)
    {
        if (!isset($this->factoryMap[$key])) {
            throw new RuntimeException(sprintf("Factory for action %s not defined", $key));
        }
        $instance = new $this->factoryMap[$key]();
        $this->instances[$key] = $instance;

        return $instance;
    }

    /**
     * @param  array $factoryMap
     * @return $this
     */
    public function setFactoryMap($factoryMap)
    {
        $this->factoryMap = $factoryMap;

        return $this;
    }

    /**
     * @return array
     */
    public function getFactoryMap()
    {
        return $this->factoryMap;
    }

    /**
     * @param  string                            $actionClass
     * @param  string|FactoryInterface $factoryClass
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function addFactory($actionClass, $factoryClass)
    {
        if ($factoryClass instanceof FactoryInterface) {
            $this->instances[$actionClass] = $factoryClass;
            $this->factoryMap[$actionClass] = get_class($factoryClass);
        } elseif (is_string($factoryClass)) {
            $this->factoryMap[$actionClass] = $factoryClass;
            if (isset($this->instances[$actionClass])) {
                // if override, remove old instance
                unset($this->instances[$actionClass]);
            }
        } else {
            throw new \InvalidArgumentException("Factory class is not a valid class");
        }

        return $this;
    }
}
