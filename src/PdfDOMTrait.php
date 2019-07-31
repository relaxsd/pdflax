<?php

namespace Relaxsd\Pdflax;

use Relaxsd\Pdflax\Helpers\Converter;
use Relaxsd\Stylesheets\Attributes\FontSize;
use Relaxsd\Stylesheets\Attributes\LineHeight;
use Relaxsd\Stylesheets\Style;

/**
 * Class PdfDOMTrait
 */
trait PdfDOMTrait
{

    /**
     * @param string                           $caption
     * @param \Relaxsd\Stylesheets\Style|array $style
     *
     * @return $this
     */
    public function h1($caption, $style = null)
    {
        $style = Style::merged($this->getStyle('h1'), $style);

        // Calculate the cell height (line height)
        $lineHeightMm = $this->calculateLineHeight($style);

        return $this->cell(0, null, '100%', $lineHeightMm, $caption, $style);
    }

    /**
     * @param string                           $caption
     * @param \Relaxsd\Stylesheets\Style|array $style
     *
     * @return $this
     */
    public function h2($caption, $style = null)
    {
        $style = Style::merged($this->getStyle('h2'), $style);

        // Calculate the cell height (line height)
        $lineHeightMm = $this->calculateLineHeight($style);

        return $this->cell(0, null, '100%', $lineHeightMm, $caption, $style);
    }

    /**
     * @param string                           $caption
     * @param \Relaxsd\Stylesheets\Style|array $style
     *
     * @return $this
     */
    public function h3($caption, $style = null)
    {
        $style = Style::merged($this->getStyle('h3'), $style);

        // Calculate the cell height (line height)
        $lineHeightMm = $this->calculateLineHeight($style);

        return $this->cell(0, null, '100%', $lineHeightMm, $caption, $style);
    }

    /**
     * @param string                           $caption
     * @param \Relaxsd\Stylesheets\Style|array $style
     *
     * @return $this
     */
    public function h4($caption, $style = null)
    {
        $style = Style::merged($this->getStyle('h4'), $style);

        // Calculate the cell height (line height)
        $lineHeightMm = $this->calculateLineHeight($style);

        return $this->cell(0, null, '100%', $lineHeightMm, $caption, $style);
    }

    /**
     * @param string                           $text
     * @param \Relaxsd\Stylesheets\Style|array $style
     *
     * @return $this
     */
    public function p($text = '', $style = null)
    {
        $style = Style::merged($this->getStyle('p'), $style);

        // Calculate the cell height (line height)
        $lineHeightMm = $this->calculateLineHeight($style);

        return $this->cell(0, null, '100%', $lineHeightMm, $text, $style);
    }

    /**
     * @param string                           $text
     * @param string                           $href
     * @param \Relaxsd\Stylesheets\Style|array $style
     *
     * @return $this
     */
    public function a($text = '', $href = '', $style = null)
    {
        $style = Style::merged($this->getStyle('a'), $style);

        // Calculate the cell height (line height)
        $lineHeightMm = $this->calculateLineHeight($style);

        return $this->text($lineHeightMm, $text, $style, ['href' => $href]);
    }

    protected function calculateLineHeight($style) {

        // Calculate the cell height (line height)
        $lineHeightFactor = Style::value($style, LineHeight::ATTRIBUTE, 1.2);
        $fontSizePt = Style::value($style, FontSize::ATTRIBUTE, FontSize::DEFAULT_VALUE);

        return $lineHeightFactor * Converter::points_to_mm($fontSizePt);
    }

}
