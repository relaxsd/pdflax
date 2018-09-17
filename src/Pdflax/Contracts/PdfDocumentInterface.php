<?php

namespace Pdflax\Contracts;

interface PdfDocumentInterface extends PdfStyleInterface, PdfDOMInterface, PdfMarginInterface
{

    const FONT_STYLE_NORMAL = 0x000;
    const FONT_STYLE_BOLD = 0x001;
    const FONT_STYLE_ITALIC = 0x010;
    const FONT_STYLE_UNDERLINE = 0x100;

    /**
     * Get the current page number.
     *
     * @return int
     */
    public function getPage();

    /**
     * Get the X-position of this document in it parent.
     *
     * @return float
     */
    public function getX();

    /**
     * Get the Y-position of this document in it parent.
     *
     * @return float
     */
    public function getY();

    /**
     * Get the width of this document in it parent.
     *
     * @return float
     */
    public function getWidth();

    /**
     * Get the height of this document in it parent.
     *
     * @return float
     */
    public function getHeight();

    /**
     * Get the (local) X-position of the cursor.
     *
     * @return float
     */
    public function getCursorX();

    /**\
     * Get the (local) Y-position of the cursor.
     *
     * @return float
     */
    public function getCursorY();

    /**
     * Position the 'cursor' at a given X
     *
     * @param float|string $x Local X-coordinate
     */
    public function setCursorX($x);

    /**
     * Position the 'cursor' at a given Y
     *
     * @param float|string $y Local Y-coordinate
     */
    public function setCursorY($y);

    /**
     * Position the 'cursor' at a given Y
     *
     * @param float|string $x Local X-coordinate
     * @param float|string $y Local Y-coordinate
     */
    public function setCursorXY($x, $y);

    /**
     * @param float|string $w
     * @param float|string $h
     * @param string       $txt
     * @param array|string $options
     */
    public function cell($w, $h = 0.0, $txt = '', $options = []);

    /**
     * @param string $family
     * @param int    $style
     * @param int    $size
     *
     * @return self
     */
    public function setFont($family, $style = self::FONT_STYLE_NORMAL, $size = 0);

    /**
     * @param string|int|array $r Red value (with $g and $b) or greyscale value ($g and $b null) or color name or [r,g,b] array
     * @param int|null         $g Green value
     * @param int|null         $b Blue value
     *
     * @return mixed
     */
    public function setDrawColor($r, $g = null, $b = null);

    /**
     * @param string|int|array $r Red value (with $g and $b) or greyscale value ($g and $b null) or color name or [r,g,b] array
     * @param int|null         $g Green value
     * @param int|null         $b Blue value
     *
     * @return mixed
     */
    public function setTextColor($r, $g = null, $b = null);

    /**
     * @param string|int|array $r Red value (with $g and $b) or greyscale value ($g and $b null) or color name or [r,g,b] array
     * @param int|null         $g Green value
     * @param int|null         $b Blue value
     *
     * @return mixed
     */
    public function setFillColor($r, $g = null, $b = null);

    /**
     * @param     $auto
     * @param int $margin
     *
     * @return PdfDocumentInterface
     */
    public function setAutoPageBreak($auto, $margin = 0);

    /**
     * @param string|null $size
     * @param string|null $orientation
     *
     * @return PdfDocumentInterface
     * @throws \Pdflax\Exceptions\UnsupportedFeatureException
     */
    public function addPage($size = null, $orientation = null);

    /**
     * @param string $fileName
     *
     * @return PdfDocumentInterface
     */
    public function save($fileName);

    /**
     * @return string
     */
    public function getPdfContent();

    /**
     * @param int        $n
     * @param array|null $options
     *
     * @return mixed
     */
    public function newLine($n = 1, $options = null);

    /**
     * @param float|string $x
     * @param float|string $y
     * @param float|string $w
     * @param float|string $h
     * @param array|string $style
     *
     * @return self
     */
    public function rectangle($x, $y, $w, $h, $style = '');

    /**
     * @param float|string $x1
     * @param float|string $y1
     * @param float|string $x2
     * @param float|string $y2
     * @param array|string $style
     *
     * @return self
     */
    public function line($x1, $y1, $x2, $y2, $style = '');

    /**
     * @param string       $file
     * @param float|string $x
     * @param float|string $y
     * @param float|string $w
     * @param float|string $h
     * @param string       $type
     * @param string       $link
     *
     * @return self
     */
    public function image($file, $x, $y, $w, $h, $type = '', $link = '');

    /**
     * @param float|string $h
     * @param string       $text
     * @param string       $link
     *
     * @return self
     */
    public function write($h, $text, $link = '');

    /**
     * @return mixed
     */
    public function raw();

}
