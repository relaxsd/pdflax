<?php

namespace Relaxsd\Pdflax\Registry;


class Registry
{

    /**
     * The list of all registered  pdfCreator values.
     *
     * @var array
     */
    protected $values = [];

    /**
     * Register an item
     *
     * @param string $key   The key
     * @param mixed  $value The value
     */
    public function register($key, $value)
    {
        $this->values[$key] = $value;
    }

    /**
     * @param $key
     *
     * @return mixed|null
     * @throws \Relaxsd\Pdflax\Registry\RegistryException
     */
    public function getValue($key)
    {
        if (isset($key) && array_key_exists($key, $this->values)) {
            return $this->values[$key];
        }

        throw new RegistryException("No value found for key '{$key}'");
    }

}
