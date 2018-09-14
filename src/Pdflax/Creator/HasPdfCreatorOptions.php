<?php

namespace Pdflax\Creator;

use Pdflax\Contracts\PdfCreatorOptionsInterface;

trait HasPdfCreatorOptions
{

    /**
     * The options to use when creating a new PdfCreator
     *
     * @var array
     */
    protected $options = [];

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    public function setOption($name, $value)
    {
        $options [$name] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function portrait()
    {
        $options['orientation'] = PdfCreatoroptionsInterface::ORIENTATION_PORTRAIT;

        return $this;
    }

    /**
     * @return $this
     */
    public function landscape()
    {
        $options['orientation'] = PdfCreatorOptionsInterface::ORIENTATION_LANDSCAPE;

        return $this;
    }

    /**
     * @param string $units
     *
     * @return $this
     */
    public function withUnits($units)
    {
        $options['units'] = $units;

        return $this;
    }

    /**
     * @return $this
     */
    public function usingMillimeters()
    {
        $options['units'] = PdfCreatoroptionsInterface::UNIT_MM;

        return $this;
    }

    /**
     * @return $this
     */
    public function usingCentimeters()
    {
        $options['units'] = PdfCreatoroptionsInterface::UNIT_CM;

        return $this;
    }

    /**
     * @return $this
     */
    public function usingInches()
    {
        $options['units'] = PdfCreatoroptionsInterface::UNIT_INCH;

        return $this;
    }

    /**
     * @return $this
     */
    public function usingPoints()
    {
        $options['units'] = PdfCreatoroptionsInterface::UNIT_PT;

        return $this;
    }

    /**
     * @return $this
     */
    public function pageSizeA4()
    {
        $options['size'] = PdfCreatoroptionsInterface::SIZE_A4;

        return $this;
    }

    /**
     * @param integer|string $width
     * @param integer|null   $height
     *
     * @return $this
     */
    public function withPageSize($width, $height = null)
    {
        if (is_string($width)) {
            $options['size'] = $width;
        } else {
            $options['size'] = [$width, $height];
        }

        return $this;
    }

}
