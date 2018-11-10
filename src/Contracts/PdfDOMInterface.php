<?php

namespace Relaxsd\Pdflax\Contracts;

interface PdfDOMInterface
{

    /**
     * @param string       $caption
     * @param array|string $style
     *
     * @return PdfDocumentInterface
     */
    public function h1($caption, $style = []);

    /**
     * @param string       $caption
     * @param array|string $style
     *
     * @return PdfDocumentInterface
     */
    public function h2($caption, $style = []);

    /**
     * @param string       $text
     * @param array|string $style
     *
     * @return PdfDocumentInterface
     */
    public function p($text = '', $style = []);

}
