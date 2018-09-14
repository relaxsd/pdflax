<?php

namespace Pdflax\Table;

use Pdflax\FpdfView;
use Pdflax\Helpers\Arr;

class FpdfTable extends FpdfView
{

    protected $options = [

        'row' => [
            'height' => '8'
        ],

        'cell' => [
            'align'  => 'L',
            'height' => '8',
            'ln'     => 0,
            'border' => 0,
        ],

        'width'  => 300, // TODO: auto-grow by adding columns
        'height' => 300, // TODO: auto-grow by adding rows

    ];

    protected $columns = [];

    protected $headerRow;

    /**
     * FpdfTable constructor.
     *
     * @param \Pdflax\Contracts\PdfDocumentInterface $document
     * @param array                                  $options
     */
    public function __construct($document, $options = [])
    {
        $this->options = Arr::mergeRecursiveConfig($this->options,
            [
                'x' => $document->getCursorX(),
                'y' => $document->getCursorY(),
            ],
            $options
        );

        // TODO
        parent::__construct($document,
            $this->options['x'],
            $this->options['y'],
            $this->options['width'],
            $this->options['height']
        // + style: []
        );

        //$this->setReferenceSize(100,100);
    }

    /**
     * @param float|string $width
     * @param array|string $styles
     *
     * @return $this
     */
    public function column($width, $styles = [])
    {

        $columnCount = count($this->columns);

        /** @var PdfTableColumn $previousColumn */
        $previousColumn = $columnCount ? $this->columns[$columnCount - 1] : null;

        // Determine the x position (float) based on the previous columns x + width (string or float)
        $x = isset($previousColumn) ? ($previousColumn->getX() + $this->eval_parent_h($previousColumn->getWidth())) : 0;

        $this->columns[] = new PdfTableColumn($this, $x, $width, $styles);

        return $this;
    }

    /**
     * @param array|string $styles
     * @param float        $h
     *
     * @return PdfTableRow
     * @internal param array $options
     */
    public function row($styles = [], $h = 10.0)
    {
        // Note that row height is passed via columns
        return new PdfTableRow($this, $h, $styles);
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
