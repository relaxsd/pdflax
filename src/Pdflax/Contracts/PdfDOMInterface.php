<?php

namespace Pdflax\Contracts;

interface PdfDOMInterface extends PdfStyleInterface
{

    /**
     * @param float $value
     *
     * @return string
     */
    public function euro($value);

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
