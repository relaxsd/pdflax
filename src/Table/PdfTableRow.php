<?php

namespace Relaxsd\Pdflax\Table;

use Relaxsd\Pdflax\PdfStyleTrait;
use Relaxsd\Stylesheets\Style;
use Relaxsd\Stylesheets\Stylesheet;

class PdfTableRow
{

    use PdfStyleTrait;

    // These will be merged with parent document styles (scaled, see FpdfView constructor)
    protected static $styles = [

        // Default table row styles
        'tr' => [
            'padding-left' => 0,
        ],

        // Default table cell styles
        'th' => [
            'border'     => 1,
            'font-style' => 'bold',
            'fill'       => 1,
            'fill-color' => 200,
        ],

        'td' => [
            'border'     => 1,
            'fill'       => 0,
            'fill-color' => 0
        ]

    ];

    /** @var  PdfTable */
    protected $table;

    /**
     * Y-coordinate of this row (within the table)
     *
     * @var float|string
     */
    protected $y = 0.0;

    /**
     * Height of this column (also supports percentages relative to the table, like "40%")
     *
     * @var float|string
     */
    protected $h = 0.0;

    protected $maxChildHeight = 0;

    protected $cells = 0;

    /**
     * PdfTableRow constructor.
     *
     * @param PdfTable                                   $table
     * @param float                                      $y
     * @param float                                      $h Row height.
     * @param \Relaxsd\Stylesheets\Stylesheet|array|null $stylesheet
     */
    public function __construct($table, $y, $h = 10.0, $stylesheet = [])
    {
        $this->table = $table;
        $this->y     = $y;
        $this->h     = $h;

        $this->stylesheet = $stylesheet = (new Stylesheet(self::$styles))->add($stylesheet);
    }

    /**
     * @param string|string[]                       $caption
     * @param \Relaxsd\Stylesheets\Style|array|null $style
     * @param array                                 $options E.g. for colspan
     *
     * @return PdfTableRow
     */
    public function th($caption, $style = null, $options = [])
    {
        // Recursive call for collections
        if (is_array($caption)) {
            foreach ($caption as $singleCaption) {
                $this->th($singleCaption, $style);
            }
            return $this;
        }

        return $this->td($caption, $this->getCurrentCellStyle('th', $style), $options);
    }

    /**
     * Draws a tabel cell.
     * - The width is taken from the column (use option 'colspan' to span multiple columns);
     * - The heigth is taken from the current row;
     *
     * @param string|string[]                       $caption
     * @param \Relaxsd\Stylesheets\Style|array|null $style
     * @param array                                 $options E.g. for colspan
     *
     * @return PdfTableRow
     */
    public function td($caption = '', $style = null, $options = [])
    {

        // Recursive call for collections
        if (is_array($caption)) {
            foreach ($caption as $singleCaption) {
                $this->td($singleCaption, $style);
            }
            return $this;
        }

        // Set default options
        $options = array_merge(['colspan' => 1], $options);

        $columnIndex = $this->cells;
        $column      = $this->table->getColumn($columnIndex);

        // Evaluate the styles already, including column styles
        $style = $this->getCurrentCellStyle('td', $style);

        // Read (and limit) the colspan
        $colspan     = $options['colspan'];
        $this->cells += $colspan;

        // Use colspan to sum columns widths
        $columnWidth = 0.0;
        for ($i = 0; $i < $colspan; $i++) {
            $col         = $this->table->getColumn($columnIndex + $i);
            $columnWidth += $col->getWidth(true);
        }

        // Support for 'padding-left' on row: indent first column
        $x = $column->getX(true);

        if ($columnIndex == 0) {
            $rowStyle = $this->getStyle('tr');
            $padding  = Style::value($rowStyle, 'padding-left', 0);

            // Add padding
            $x           += $padding;
            $columnWidth -= $padding;
        }

        $this->table->setCursorXY($x, $this->y);
        $this->table->cell($columnWidth, $this->h, $caption, $style);

        // Remember the height of this row
        $this->maxChildHeight = max($this->maxChildHeight, $this->getHeight(true), $this->table->getCursorY() - $this->y);

        // We don't know if this is the last cell, but always move the cursor
        // to the bottom-left of this row (to start a new row)
        $this->table->setCursorXY(0.0, $this->maxChildHeight);

        return $this;
    }

    /**
     *
     * @param string $element
     * @param        $style
     *
     * @return \Relaxsd\Stylesheets\Style
     */
    protected function getCurrentCellStyle($element, $style = null)
    {
        $currentColumn = $this->table->getColumn($this->cells);

        $tableStyle  = $this->table->getStyle($element);
        $columnStyle = $currentColumn ? $currentColumn->getStyle($element) : null;
        $rowStyle    = $this->getStyle($element);

        return Style::merged($tableStyle, $columnStyle, $rowStyle, $style);
    }

    /**
     * @param boolean $parsed
     *
     * @return float|string
     */
    public function getY($parsed = false)
    {
        return $parsed
            ? $this->parseLocalValue_v($this->y)
            : $this->y;
    }

    /**
     * @param boolean $parsed
     *
     * @return float|string
     */
    public function getHeight($parsed = false)
    {
        return $parsed
            ? $this->parseLocalValue_v($this->h)
            : $this->h;
    }

    /**
     * @param float|string|null $localValue
     *
     * @return float|null
     */
    protected function parseLocalValue_v($localValue)
    {
        return (is_string($localValue))
            ? $this->table->getLocalHeight() * floatval($localValue) / 100
            : $localValue;
    }

}
