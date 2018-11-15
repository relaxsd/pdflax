<?php

namespace Relaxsd\Pdflax\Contracts;

interface PdfStyleInterface
{

    /**
     * @param \Relaxsd\Stylesheets\Stylesheet|array $stylesheet
     *
     * @return $this
     */
    public function addStylesheet($stylesheet);

    /**
     * Get all available styles.
     *
     * @return \Relaxsd\Stylesheets\Stylesheet
     */
    //public function getStylesheet();

    /**
     * @param string $element
     *
     * @return null|\Relaxsd\Stylesheets\Style
     */
    //public function getStyle($element);

}
