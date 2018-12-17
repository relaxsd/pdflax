<?php

namespace Relaxsd\Pdflax\Table;

use Relaxsd\Pdflax\PdfView;
use Relaxsd\Stylesheets\Stylesheet;

class PdfTable extends PdfView
{

    protected static $styles = [

        'row' => [
            'height' => '8'
        ],

        'cell' => [
            'align'  => 'left',
            'height' => '8',
            'ln'     => 0,
            'border' => 0,
        ],

    ];

    protected $columns = [];

    protected $rows = [];

    protected $headerRow;

    /**
     * PdfTable constructor.
     *
     * @param \Relaxsd\Pdflax\Contracts\PdfDocumentInterface $document
     * @param float|string|null                              $x
     * @param float|string|null                              $y
     * @param float|string                                   $w
     * @param float|string                                   $h
     * @param \Relaxsd\Stylesheets\Stylesheet|array|null     $stylesheet
     */
    public function __construct($document, $x = null, $y = null, $w = 0.0, $h = 0.0, $stylesheet = [])
    {

        $x = isset($x) ? $x : $document->getCursorX();
        $y = isset($y) ? $y : $document->getCursorY();

        $stylesheet = (new Stylesheet(self::$styles))->add($stylesheet);

        parent::__construct($document, $x, $y, $w, $h, $stylesheet);
        //$this->setReferenceSize(100,100);
    }

    /**
     * @param float|string                               $width
     * @param \Relaxsd\Stylesheets\Stylesheet|array|null $stylesheet
     *
     * @return $this
     */
    public function column($width, $stylesheet = [])
    {

        $columnCount = count($this->columns);

        /** @var PdfTableColumn $previousColumn */
        $previousColumn = $columnCount
            ? $this->columns[$columnCount - 1]
            : null;

        // Determine the x position (float) based on the previous columns x + width (both string or float)
        $x = isset($previousColumn)
            ? ($previousColumn->getX(true) + $previousColumn->getWidth(true))
            : 0;

        $this->columns[] = new PdfTableColumn($this, $x, $width, $stylesheet);

        return $this;
    }

    /**
     * @param float                                      $h
     * @param \Relaxsd\Stylesheets\Stylesheet|array|null $stylesheet
     *
     * @return PdfTableRow
     */
    public function row($h = 10.0, $stylesheet = [])
    {
        $rowCount = count($this->rows);

        /** @var PdfTableRow $previousRow */
        $previousRow = $rowCount
            ? $this->rows[$rowCount - 1]
            : null;

        // Determine the x position (float) based on the previous rows x + width (both string or float)
        $y = isset($previousRow)
            ? ($previousRow->getY(true) + $previousRow->getHeight(true))
            : 0;

        $this->rows[] = $row = new PdfTableRow($this, $y, $h, $stylesheet);

        return $row;

    }

    /**
     * @param int $index
     *
     * @return PdfTableColumn
     */
    public function getColumn($index)
    {
        return $this->columns[$index];
    }

    /**
     * @return int
     */
    public function getColumnCount()
    {
        return count($this->columns);
    }

}
