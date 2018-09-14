<?php

namespace Pdflax\Contracts;

interface PdfStyleInterface
{

    /**
     * @param array $stylesheet
     *
     * @return PdfDocumentInterface
     */
    public function addStylesheet($stylesheet);

    /**
     * Get all available styles.
     *
     * @return array
     */
    public function getStylesheet();

    /**
     * @param array|string      $style1 Style(s) to merge.
     * @param array|string|null $styleN
     *
     * @return array
     */
    public function getStyle($style1, $styleN = null);

    /**
     * @param array $style
     *
     * @return PdfDocumentInterface
     */
    public function applyStyle(array $style);

}
