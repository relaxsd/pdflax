<?php

namespace Relaxsd\Pdflax\Table;

use Relaxsd\Pdflax\PdfStyleTrait;
use Relaxsd\Stylesheets\Stylesheet;

class PdfTableColumn
{

    use PdfStyleTrait;

    /** @var  PdfTable */
    protected $table;

    /**
     * X-coordinate of this column (within the table)
     *
     * @var float|string
     */
    protected $x = 0.0;

    /**
     * Width of this column (also supports percentages relative to the table, like "40%")
     *
     * @var float|string
     */
    protected $w = 0.0;

    /**
     * PdfTableColumn constructor.
     *
     * @param PdfTable                                   $table
     * @param float                                      $x
     * @param float|string                               $w
     * @param \Relaxsd\Stylesheets\Stylesheet|array|null $stylesheet
     */
    public function __construct($table, $x, $w = 20.0, $stylesheet = [])
    {
        $this->table = $table;
        $this->x     = $x;
        $this->w     = $w;

        $this->stylesheet = Stylesheet::stylesheet($stylesheet);
    }

    /**
     * @param boolean $parsed
     *
     * @return float|string
     */
    public function getX($parsed = false)
    {
        return $parsed
            ? $this->parseLocalValue_h($this->x)
            : $this->x;
    }

    /**
     * @param boolean $parsed
     *
     * @return float|string
     */
    public function getWidth($parsed = false)
    {
        return $parsed
            ? $this->parseLocalValue_h($this->w)
            : $this->w;
    }

    /**
     * @param float|string|null $localValue
     *
     * @return float|null
     */
    protected function parseLocalValue_h($localValue)
    {
        return (is_string($localValue))
            ? $this->table->getLocalWidth() * floatval($localValue) / 100
            : $localValue;
    }

}
