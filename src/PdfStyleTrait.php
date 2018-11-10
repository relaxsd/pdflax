<?php

namespace Relaxsd\Pdflax;

/**
 * Class PdfStyleTrait
 */
trait PdfStyleTrait
{

    /**
     * @var \Relaxsd\Pdflax\Style\Stylesheet
     */
    protected $stylesheet;

    /**
     * Get all available styles.
     *
     * @return \Relaxsd\Pdflax\Style\Stylesheet
     */
    public function getStylesheet()
    {
        return $this->stylesheet;
    }

    /**
     * @param \Relaxsd\Pdflax\Style\Stylesheet $stylesheet
     *
     * @return $this
     */
    public function addStylesheet($stylesheet)
    {
        if (isset($this->stylesheet)) {
            $this->stylesheet->mergeStylesheets($stylesheet);
        } else {
            $this->stylesheet = $stylesheet;
        }

        return $this;
    }

    /**
     * @param string $element
     *
     * @return null|\Pdflax\Style\Styles
     */
    public function getStyles($element)
    {
        return $this->stylesheet
            ? $this->stylesheet->getStyles($element)
            : null;
    }

}
