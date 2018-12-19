<?php

namespace Relaxsd\Pdflax;

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

        // TODO: Hardcoded height
        return $this->cell(0, null, '100%', 8, $caption, $style);
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

        // TODO: Hardcoded height
        return $this->cell(0, null, '100%', 8, $caption, $style);
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

        // TODO: Hardcoded height
        return $this->cell(0, null, '100%', 6, $text, $style);
    }

}
