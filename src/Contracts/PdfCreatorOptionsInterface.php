<?php

namespace Relaxsd\Pdflax\Contracts;

interface PdfCreatorOptionsInterface
{

    const ORIENTATION_PORTRAIT = 'portrait';
    const ORIENTATION_LANDSCAPE = 'landscape';

    const UNIT_PT = 'pt';
    const UNIT_MM = 'mm';
    const UNIT_CM = 'cm';
    const UNIT_INCH = 'inch';

    const SIZE_A4 = 'A4';

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

}
