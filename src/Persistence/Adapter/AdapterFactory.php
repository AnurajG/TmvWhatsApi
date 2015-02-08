<?php

namespace Tmv\WhatsApi\Persistence\Adapter;

class AdapterFactory
{
    /**
     * @param  array $config
     * @return AdapterInterface
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public static function factory(array $config)
    {
        if (!isset($config['class'])) {
            throw new \InvalidArgumentException("Missing key 'class' in configuration");
        }

        $name = $className = $config['class'];

        if (!class_exists($config['class'])) {
            $name = ucfirst($config['class']);
            $className = __NAMESPACE__.'\\'.$name.'Adapter';
        }

        if (!class_exists($className)) {
            throw new \InvalidArgumentException('Missing adapter class.');
        }

        if (!is_subclass_of($className, __NAMESPACE__ . '\\AdapterInterface')) {
            throw new \RuntimeException(sprintf("Adapter '%s' is not valid", $name));
        }

        $instance = call_user_func([$className, 'factory'], $config);

        return $instance;
    }
}
