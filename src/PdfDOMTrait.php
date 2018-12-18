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

        $this->setCursorX(0);

        $width = $this->getInnerWidth();

        return $this->cell($width, 8, $caption, $style);
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

        $this->setCursorX(0);

        $width = $this->getInnerWidth();

        // TODO: Use $this->fpdf->FontSizePt?
        return $this->cell($width, 8, $caption, $style);
    }

    /**
     * @param string       $text
     * @param \Relaxsd\Stylesheets\Style|array $style
     *
     * @return $this
     */
    public function p($text = '', $style = null)
    {
        $style = Style::merged($this->getStyle('p'), $style);

        $this->setCursorX(0);

        $width = $this->getInnerWidth();

        // TODO: Use $this->fpdf->FontSizePt?
        return $this->cell($width, 6, $text, $style);
    }

}
