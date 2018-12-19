<?php

namespace Relaxsd\Pdflax\Contracts;

interface PdfDocumentInterface extends PdfDOMInterface, PdfMarginInterface, PdfFormattingInterface, PdfStyleInterface
{

    /**
     * Get the current page number.
     *
     * @return int
     */
    public function getPage();

    /**
     * Get the X-position of this document in its parent
     *
     * @return float
     */
    public function getX();

    /**
     * Get the Y-position of this document in its parent
     *
     * @return float
     */
    public function getY();

    /**
     * Get the total width of this document, including its left and right margins
     *
     * @return float
     */
    public function getWidth();

    /**
     * Get the total height of this document, including its top and bottom margins
     *
     * @return float
     */
    public function getHeight();

    /**
     * Get the inner width of this document, between its left and right margins
     *
     * @return float
     */
    public function getInnerWidth();

    /**
     * Get the inner height of this document, between its top and bottom margins
     *
     * @return float
     */
    public function getInnerHeight();

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
     *
     * @return $this
     */
    public function setCursorX($x);

    /**
     * Position the 'cursor' at a given Y
     *
     * @param float|string $y Local Y-coordinate
     *
     * @return $this
     */
    public function setCursorY($y);

    /**
     * Position the 'cursor' at a given Y
     *
     * @param float|string $x Local X-coordinate
     * @param float|string $y Local Y-coordinate
     *
     * @return $this
     */
    public function setCursorXY($x, $y);

    /**
     * Move the 'cursor' horizontally
     *
     * @param float|string $d distance
     *
     * @return $this
     */
    public function moveCursorX($d);

    /**
     * Move the 'cursor' vertically
     *
     * @param float|string $d distance
     *
     * @return $this
     */
    public function moveCursorY($d);

    /**
     * @param float|string              $w
     * @param float|string              $h
     * @param string                    $txt
     * @param \Relaxsd\Stylesheets\Style|array|null $style
     *
     * @return $this
     */
    public function cell($w, $h = 0.0, $txt = '', $style = null);

    /**
     * @param     $auto
     * @param int $margin
     *
     * @return $this
     */
    public function setAutoPageBreak($auto, $margin = 0);

    /**
     * @param string|null $orientation
     * @param string|null $size
     *
     * @return $this
     */
    public function addPage($orientation = null, $size = null);

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setFontPath($path);

    /**
     * @param string $family
     * @param integer $style
     * @param string $filename
     *
     * @return $this
     */
    public function registerFont($family, $style, $filename);

    /**
     * @param string $fileName
     *
     * @return $this
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
     * @param float|string              $x
     * @param float|string              $y
     * @param float|string              $w
     * @param float|string              $h
     * @param \Relaxsd\Stylesheets\Style|array|null $style
     *
     * @return $this
     */
    public function rectangle($x, $y, $w, $h, $style = null);

    /**
     * @param float|string               $x1
     * @param float|string               $y1
     * @param float|string               $x2
     * @param float|string               $y2
     * @param \Relaxsd\Stylesheets\Style|array|null $style
     *
     * @return $this
     */
    public function line($x1, $y1, $x2, $y2, $style = null);

    /**
     * @param string                     $file
     * @param float|string               $x
     * @param float|string               $y
     * @param float|string               $w
     * @param float|string               $h
     * @param string                     $type
     * @param string                     $link
     * @param \Relaxsd\Stylesheets\Style|array|null $style
     *
     *
     * @return $this
     */
    public function image($file, $x, $y, $w, $h, $type = '', $link = '', $style = null);

    /**
     * @param float|string               $h
     * @param string                     $text
     * @param string                     $link
     * @param \Relaxsd\Stylesheets\Style|array|null $style
     *
     * @return $this
     */
    public function write($h, $text, $link = '', $style = null);

    /**
     * @return mixed
     */
    public function raw();

}

