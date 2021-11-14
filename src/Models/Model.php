<?php

namespace App\Models;

class Model
{
    protected array $propNames = [];
    protected array $props = [];

    public function __construct(array $props)
    {
        foreach ($this->propNames as $prop) {
            $this->props[$prop] = $props[$prop] ?? null;
        }
    }

    public function __get(string $name)
    {
        $this->checkPropName($name);
        return $this->props[$name];
    }

    public function __set(string $name, $value)
    {
        $this->checkPropName($name);
        $this->props[$name] = $value;
    }

    protected function checkPropName(string $name, bool $suppressException = false): bool
    {
        if (!in_array($name, $this->propNames)) {
            if (!$suppressException) {
                throw new \DomainException("Invalid property: $name");
            }
            return false;
        }
        return true;
    }

    public function getProps(): array
    {
        return $this->props;
    }
}