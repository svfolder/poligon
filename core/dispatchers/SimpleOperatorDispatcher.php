<?php

namespace core\dispatchers;

use yii\di\Container;

class SimpleOperatorDispatcher implements OperatorDispatcher
{
    private $container;
    private $listeners;

    public function __construct(Container $container, array $listeners)
    {
        $this->container = $container;
        $this->listeners = $listeners;
    }

    public function dispatchAll(array $operators): void
    {
        foreach ($operators as $operator) {
            $this->dispatch($operator);
        }
    }

    public function dispatch($operator): void
    {
        $operatorName = get_class($operator);
        if (array_key_exists($operatorName, $this->listeners)) {
            foreach ($this->listeners[$operatorName] as $listenerClass) {
                $listener = $this->resolveListener($listenerClass);
                $listener($operator);
            }
        }
    }

    private function resolveListener($listenerClass): callable
    {
        return [$this->container->get($listenerClass), 'handle'];
    }

    public function operators(): array
    {
        $buffer = [];
        foreach (array_keys($this->listeners) as $listener){
            $listener = str_replace('core\\operator\\', '', $listener);
            $buffer[$listener] = $listener;
        }
        return $buffer;
    }
}