<?php

namespace Relaxsd\Pdflax;

use Relaxsd\Stylesheets\Style;

/**
 * Class PdfDOMTrait
 */
trait PdfDOMTrait
{

    /**
     * @param string       $caption
     * @param \Relaxsd\Stylesheets\Style|array $style
     *
     * @return $this
     */
    public function h1($caption, $style = null)
    {
        $style = Style::merged($this->getStyle('h1'), $style);

        $this->setCursorX($this->getLeftMargin());

        $width = $this->getWidth() - $this->getLeftMargin() - $this->getRightMargin();

        return $this->block($width, 8, $caption, $style);
    }

    /**
     * @param string       $caption
     * @param \Relaxsd\Stylesheets\Style|array $style
     *
     * @return $this
     */
    public function h2($caption, $style = null)
    {
        $style = Style::merged($this->getStyle('h2'), $style);

        $this->setCursorX($this->getLeftMargin());

        $width = $this->getWidth() - $this->getLeftMargin() - $this->getRightMargin();

        // TODO: Use $this->fpdf->FontSizePt?
        return $this->block($width, 8, $caption, $style);
    }

    /**
     * @param string       $text
     * @param \Relaxsd\Stylesheets\Style|array $style
     *
     * @return $this
     */
    public function p($text = '', $style = null)
    {
        $style = Style::merged($this->getStyle('h3'), $style);

        $this->setCursorX($this->getLeftMargin());

        $width = $this->getWidth() - $this->getLeftMargin() - $this->getRightMargin();

        // TODO: Use $this->fpdf->FontSizePt?
        return $this->block($width, 6, $text, $style);
    }

    /**
     * @param float|string $width
     * @param float|string $height
     * @param string       $caption
     * @param \Relaxsd\Stylesheets\Style|array $style
     *
     * @return $this
     */
    public function block($width, $height, $caption, $style = null)
    {
        $style = Style::merged($this->getStyle('block'), $style);

        return $this->cell($width, $height, $caption, $style);
    }
}
