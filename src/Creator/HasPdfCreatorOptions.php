<?php

namespace Relaxsd\Pdflax\Creator;

use Relaxsd\Pdflax\Contracts\PdfCreatorOptionsInterface;
use Relaxsd\Stylesheets\Attributes\PageOrientation;
use Relaxsd\Stylesheets\Attributes\PageSize;

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
        $this->options[$name] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function getOption($name)
    {
        return $this->options[$name];
    }

    /**
     * @return $this
     */
    public function portrait()
    {
        $this->options['orientation'] = PageOrientation::ORIENTATION_PORTRAIT;

        return $this;
    }

    /**
     * @return $this
     */
    public function landscape()
    {
        $this->options['orientation'] = PageOrientation::ORIENTATION_LANDSCAPE;

        return $this;
    }

    /**
     * @param string $units
     *
     * @return $this
     */
    public function withUnits($units)
    {
        $this->options['units'] = $units;

        return $this;
    }

    /**
     * @return $this
     */
    public function usingMillimeters()
    {
        $this->options['units'] = PdfCreatoroptionsInterface::UNIT_MM;

        return $this;
    }

    /**
     * @return $this
     */
    public function usingCentimeters()
    {
        $this->options['units'] = PdfCreatoroptionsInterface::UNIT_CM;

        return $this;
    }

    /**
     * @return $this
     */
    public function usingInches()
    {
        $this->options['units'] = PdfCreatoroptionsInterface::UNIT_INCH;

        return $this;
    }

    /**
     * @return $this
     */
    public function usingPoints()
    {
        $this->options['units'] = PdfCreatoroptionsInterface::UNIT_PT;

        return $this;
    }

    /**
     * @return $this
     */
    public function pageSizeA4()
    {
        $this->options['size'] = PageSize::SIZE_A4;

        return $this;
    }

    /**
     * @param float $left
     * @param float $right
     * @param float $top
     * @param float $bottom
     *
     * @return $this
     */
    public function withMargins($left, $right = 0.0, $top = 0.0, $bottom = 0.0)
    {
        $this->options['margins'] = [$left, $right, $top, $bottom];

        return $this;
    }

    /**
     * @return $this
     */
    public function withoutMargins()
    {
        $this->options['margins'] = [0.0, 0.0, 0.0, 0.0];

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
            $this->options['size'] = $width;
        } else {
            $this->options['size'] = [$width, $height];
        }

        return $this;
    }

    /**
     * @param boolean $value
     *
     * @return $this
     */
    public function useCompression($value = true)
    {
        $this->options['compression'] = $value;

        return $this;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setFontPath($path)
    {
        $this->options['font-path'] = $path;

        return $this;
    }
}
