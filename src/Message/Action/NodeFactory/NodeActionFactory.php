<?php

namespace Tmv\WhatsApi\Message\Action\NodeFactory;

use Tmv\WhatsApi\Message\Action\ActionInterface;
use RuntimeException;

class NodeActionFactory
{
    /**
     * Factory Map
     *
     * @var array
     */
    protected $factoryMap = array(
        'Tmv\\WhatsApi\\Message\\Action\\MessageText' => 'Tmv\\WhatsApi\\Message\\Action\\NodeFactory\\MessageTextNodeFactory',
        'Tmv\\WhatsApi\\Message\\Action\\ChatState' => 'Tmv\\WhatsApi\\Message\\Action\\NodeFactory\\ChatStateNodeFactory',
        'Tmv\\WhatsApi\\Message\\Action\\ClearDirty' => 'Tmv\\WhatsApi\\Message\\Action\\NodeFactory\\ClearDirtyNodeFactory',
        'Tmv\\WhatsApi\\Message\\Action\\Presence' => 'Tmv\\WhatsApi\\Message\\Action\\NodeFactory\\PresenceNodeFactory',
        'Tmv\\WhatsApi\\Message\\Action\\Receipt' => 'Tmv\\WhatsApi\\Message\\Action\\NodeFactory\\ReceiptNodeFactory',
    );
    /**
     * Already instanced factories
     *
     * @var array
     */
    protected $instances = array();

    /**
     * @param ActionInterface $action
     * @return \Tmv\WhatsApi\Message\Node\NodeInterface
     * @throws \RuntimeException
     */
    public function createNode(ActionInterface $action)
    {
        $factory = null;
        if (!isset($this->factoryMap[get_class($action)])) {
            throw new RuntimeException("Factory class not defined");
        }
        /** @var NodeActionFactoryInterface $factory */
        $factory = new $this->factoryMap[get_class($action)];
        return $factory->createNode($action);
    }

    /**
     * @param ActionInterface $action
     * @return NodeActionFactoryInterface
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
     * @param string $key
     * @return NodeActionFactoryInterface
     * @throws \RuntimeException
     */
    protected function createFactory($key)
    {
        if (!isset($this->factoryMap[$key])) {
            throw new RuntimeException(sprintf("Factory for action %s not defined", $key));
        }
        $instance = new $this->factoryMap[$key];
        $this->instances[$key] = $instance;
        return $instance;
    }

    /**
     * @param array $factoryMap
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
     * @param string $actionClass
     * @param string|NodeActionFactoryInterface $factoryClass
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function addFactory($actionClass, $factoryClass)
    {
        if ($factoryClass instanceof NodeActionFactoryInterface) {
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
