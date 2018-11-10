<?php

namespace Relaxsd\Pdflax\Table;

class PdfTableColumn
{

    /** @var  PdfTable */
    protected $table;

    /**
     * X-coodinate of this column
     *
     * @var float
     */
    protected $x = 0;

    /**
     * Width of this column (also supports percentages, like "40%")
     *
     * @var float|string
     */
    protected $w = 0;

    /**
     * PDF styles for this column
     * - contain styles like [ 'align' => 'R' ]
     * - contain class names or a mix like [ 'h1', [ 'align' => 'R']]
     *
     * @var  array
     */
    protected $styles;

    /**
     * PdfTableColumn constructor.
     *
     * @param PdfTable     $table
     * @param float        $x
     * @param float|string $w
     * @param array|string $styles
     */
    public function __construct($table, $x, $w = 20.0, $styles = [])
    {
        $this->table  = $table;
        $this->x      = $x;
        $this->w      = $w;
        $this->styles = (array)$styles;
    }

    /**
     * @return float
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @return float|string
     */
    public function getWidth()
    {
        return $this->w;
    }

    /**
     * Return PDF styles for this column.
     * Always an array, containing styles names or stylesheet.
     *
     * @return array
     */
    public function getStyles()
    {
        return $this->styles;
    }

}
