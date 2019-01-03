<?php

namespace Relaxsd\Pdflax\Contracts;

interface PdfOutputInterface
{

    /**
     * @param float|string|null                     $x X position (may be percentage). If null, use current cursor position.
     * @param float|string|null                     $y Y position (may be percentage). If null, use current cursor position.
     * @param float|string|null                     $w Cell width  (may be percentage). If null, use right margin.
     * @param float|string|null                     $h Cell height (may be percentage). If null, use bottom margin
     * @param string                                $txt
     * @param \Relaxsd\Stylesheets\Style|array|null $style
     *
     * @return $this
     */
    public function cell($x = null, $y = null, $w = null, $h = null, $txt = '', $style = null);

    /**
     * @param float|string|null                     $h Text height (may be percentage). If null, use bottom margin
     * @param string                                $txt
     * @param \Relaxsd\Stylesheets\Style|array|null $style
     * @param array                                 $options
     *
     * @return $this
     */
    public function text($h = null, $txt = '', $style = null, $options = []);

}
