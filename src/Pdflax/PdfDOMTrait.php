<?php

namespace Pdflax;


/**
 * Class PdfDOMTrait
 */
trait PdfDOMTrait
{

    /**
     * @param string       $caption
     * @param array|string $style
     *
     * @return $this
     */
    public function h1($caption, $style = [])
    {
        $this->setCursorX($this->getLeftMargin());

        $width = $this->getWidth() - $this->getLeftMargin() - $this->getRightMargin();

        // TODO: Use $this->fpdf->FontSizePt?
        return $this->block($width, 8, $caption,['h1', $style]);
    }

    /**
     * @param string       $caption
     * @param array|string $style
     *
     * @return $this
     */
    public function h2($caption, $style = [])
    {
        $this->setCursorX($this->getLeftMargin());

        $width = $this->getWidth() - $this->getLeftMargin() - $this->getRightMargin();

        // TODO: Use $this->fpdf->FontSizePt?
        return $this->block($width, 8, $caption, ['h2', $style]);
    }

    /**
     * @param string       $text
     * @param array|string $style
     *
     * @return $this
     */
    public function p($text = '', $style = [])
    {

        $this->setCursorX($this->getLeftMargin());

        $width = $this->getWidth() - $this->getLeftMargin() - $this->getRightMargin();

        // TODO: Use $this->fpdf->FontSizePt?
        return $this->block($width, 6, $text, ['p', $style]);
    }

    /**
     * @param float|string       $width
     * @param float|string        $height
     * @param string       $caption
     * @param array|string $style
     *
     * @return $this
     */
    public function block($width, $height, $caption, $style = [])
    {

        $style = $this->getStyle('block', $style);
        $this->applyStyle($style);

        $this->cell($width, $height, $caption, $style);

        return $this;
    }
}
