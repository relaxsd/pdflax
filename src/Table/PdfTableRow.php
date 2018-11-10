<?php

namespace Relaxsd\Pdflax\Table;

use Relaxsd\Pdflax\PdfView;

class PdfTableRow extends PdfView
{

    // These will be merged with parent document styles (scaled, see FpdfView constructor)
    protected $stylesheet = [

        // Default table row styles
        'tr' => [
            'tr-padding-left' => 0,
        ],

        // Default table cell styles
        'th' => [
            'border'     => 'B',
            'font-style' => 'B',
            'fill'       => 1,
            'fill-color' => 200,
        ],
        'td' => [
            // Will overwrite row setting
            // 'border'     => 1,
            'fill'       => 0,
            'fill-color' => 0
        ]
    ];

    /** @var  PdfTable */
    protected $table;

    protected $maxChildHeight = 0;

    protected $cells = 0;

    /**
     * @var array|string
     */
    protected $styles = [];

    /**
     * PdfTableRow constructor.
     *
     * @param PdfTable     $table
     * @param float        $h Row height.
     * @param array|string $styles
     */
    public function __construct($table, $h = 10.0, $styles = [])
    {
        // Call parent constructor. It will also initialize our styles.
        parent::__construct($table, 0, $table->getCursorY(), '100%', $h);

        $this->styles = $styles;

        // $this->pdf (a PdfDocumentInterface) and $this->table are the same.
        // Store separately for type safety and readability
        $this->table = $table;
    }

    /**
     * @param string|string[] $caption
     * @param array           $styles
     *
     * @return PdfTableRow
     */
    public function th($caption, $styles = [])
    {
        return $this->td($caption, ['th', $styles]);
    }

    /**
     * Draws a tabel cell.
     * - The width is taken from the column (use option 'colspan' to span multiple columns);
     * - The heigth is taken from the current row;
     *
     * @param string|string[] $caption
     * @param array|string    $styles
     *
     * @return PdfTableRow
     */
    public function td($caption = '', $styles = [])
    {

        // Recursive call for collections
        if (is_array($caption)) {
            foreach ($caption as $singleCaption) {
                $this->td($singleCaption, $styles);
            }
            return $this;
        }

        $columnIndex = $this->cells;
        $column      = $this->table->getColumn($columnIndex);

        // Evaluate the styles already, including column styles
        $styles = $this->getStyle([$column->getStyles(), 'tr', $this->getStyles(), 'td', $styles]);

        // Read (and limit) the colspan
        // TODO: colspan should not be a style but an attribute
        $colspan     = min(isset($styles['colspan']) ? $styles['colspan'] : 1, $this->table->getColumnCount() - $columnIndex);
        $this->cells += $colspan;

        // Use colspan to sum columns widths
        $columnWidth = 0.0;
        for ($i = 0; $i < $colspan; $i++) {
            $col         = $this->table->getColumn($columnIndex + $i);
            $columnWidth += $this->parseGlobalValue_h($col->getWidth());
        }

        // Support for 'tr-padding-left': indent first column
        $x = $column->getX();
        if ($columnIndex == 0) {
            $x           += $styles['tr-padding-left'];
            $columnWidth -= $styles['tr-padding-left'];
        }

        $this->setCursorXY($x, 0);
        $this->block($columnWidth, $this->h, $caption, $styles);

        // Move the cursor to the bottom-left of this row
        // $this->setCursorXY(0.0, "100%");
        $this->maxChildHeight = max($this->maxChildHeight, $this->localHeight, $this->getCursorY());
        $this->setCursorXY(0.0, $this->maxChildHeight);

        return $this;
    }

    /**
     * @return array|string
     */
    public function getStyles()
    {
        return $this->styles;
    }

}
