<?php

namespace Pdflax\Creator;

use Pdflax\Contracts\PdfCreatorInterface;
use Pdflax\Contracts\PdfDocumentInterface;
use Pdflax\Registry\RegistryException;
use Pdflax\Registry\RegistryWithDefault;

class PdfCreatorRegistry extends PdfCreator
{

    /**
     * @var \Pdflax\Registry\RegistryWithDefault
     */
    protected $register;

    /**
     * The implementation to use when creating a new PdfCreator
     *
     * @var string
     */
    protected $implementation = '';

    /**
     * PdfCreatorRegistry constructor.
     *
     * @param \Pdflax\Registry\RegistryWithDefault $register
     */
    public function __construct(RegistryWithDefault $register)
    {
        $this->register = $register;
    }

    /**
     * @param string $implementation
     *
     * @return $this
     */
    public function setImplementation($implementation)
    {
        $this->implementation = $implementation;

        return $this;
    }

    /**
     * @return string
     */
    public function getImplementation()
    {
        return $this->implementation;
    }

    /**
     * Register a pfd creator implementation
     *
     * @param string $implementation The name of the implementation
     * @param string $className      The PdfCreator class (should implement PdfCreatorInterface)
     * @param null   $useAsDefault   True to use this implementation as default, false to never use it as default, leave null for 'auto'.
     *
     * @return $this
     */
    public function register($implementation, $className, $useAsDefault = null)
    {
        $this->register->register($implementation, $className, $useAsDefault);

        return $this;
    }

    /**
     * Create a PDF document, using the given options.
     *
     * Some factories allow configuration of options beforehand, e.g. with setOption() or pageSizeA4(),
     * in that case the options given here will be merged with the earlier given options.
     *
     * @param array $options
     *
     * @return PdfDocumentInterface
     * @throws \Pdflax\Creator\PdfCreatorException
     */
    public function create($options = [])
    {
        try {

            $pfdCreatorClass = $this->implementation
                ? $this->register->getValue($this->implementation)
                : $this->register->getDefaultValue();

            /** @var PdfCreatorInterface $pfdCreator */
            $pfdCreator = new $pfdCreatorClass();

            // Use the new instance, merging the options we got before
            return $pfdCreator->create(
                array_merge($this->options, $options)
            );

        } catch (RegistryException $re) {
            throw new PdfCreatorException(
                "Cannot create pdf factory for implementation '" . $this->getImplementation() . "'.",
                0,
                $re);

        }


    }


}
