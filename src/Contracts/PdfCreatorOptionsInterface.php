<?php

namespace Relaxsd\Pdflax\Contracts;

interface PdfCreatorOptionsInterface
{

    const UNIT_PT = 'pt';
    const UNIT_MM = 'mm';
    const UNIT_CM = 'cm';
    const UNIT_INCH = 'inch';

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     */
    public function setOption($name, $value);

    /**
     * @return $this
     */
    public function portrait();

    /**
     * @return $this
     */
    public function landscape();

    /**
     * @param string $units
     *
     * @return $this
     */
    public function withUnits($units);

    /**
     * @return $this
     */
    public function usingMillimeters();

    /**
     * @return $this
     */
    public function usingCentimeters();

    /**
     * @return $this
     */
    public function usingInches();

    /**
     * @return $this
     */
    public function usingPoints();

    /**
     * @return $this
     */
    public function pageSizeA4();

    /**
     * @param integer|string $width
     * @param integer|null   $height
     *
     * @return $this
     */
    public function withPageSize($width, $height = null);

    /**
     * @param boolean $value
     *
     * @return $this
     */
    public function useCompression($value = true);

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setFontPath($path);

    /**
     * @param float $left
     * @param float $right
     * @param float $top
     * @param float $bottom
     *
     * @return $this
     */
    public function withMargins($left, $right = 0.0, $top = 0.0, $bottom = 0.0);

    /**
     * @return $this
     */
    public function withoutMargins();

}
