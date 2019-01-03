<?php

namespace Relaxsd\Pdflax\Registry;

class RegistryWithDefault extends Registry
{

    /**
     * The default pdfCreator implementations to use
     *
     * @var string
     */
    protected $defaultKey;

    /**
     * Register a pfd creator factory implementation
     *
     * @param string $key          The key
     * @param mixed  $value        The value
     * @param null   $useAsDefault True to use this key as default, false to never use it as default, leave null for 'auto'.
     */
    public function register($key, $value, $useAsDefault = null)
    {
        parent::register($key, $value);

        if ($useAsDefault || (is_null($useAsDefault) && is_null($this->defaultKey))) {
            $this->defaultKey = $key;
        }
    }

    public function registerDefault($key, $value)
    {
        $this->register($key, $value, true);
    }

    /**
     * @param string $defaultKey
     *
     * @return $this
     */
    public function setDefaultKey($defaultKey)
    {
        $this->defaultKey = $defaultKey;

        return $this;
    }

    /**
     * @return mixed|null
     * @throws \Relaxsd\Pdflax\Registry\RegistryException
     */
    public function getDefaultValue()
    {
        return $this->getValue($this->defaultKey);
    }

}
