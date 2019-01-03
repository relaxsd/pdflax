<?php

namespace Relaxsd\Pdflax;

use Relaxsd\Stylesheets\Stylesheet;

/**
 * Class PdfStyleTrait
 */
trait PdfStyleTrait
{

    /**
     * @var \Relaxsd\Stylesheets\Stylesheet
     */
    protected $stylesheet;

    /**
     * Get all available styles.
     *
     * @return \Relaxsd\Stylesheets\Stylesheet
     */
    public function getStylesheet()
    {
        return $this->stylesheet;
    }

    /**
     * @param \Relaxsd\Stylesheets\Stylesheet|array $stylesheet
     *
     * @return $this
     */
    public function addStylesheet($stylesheet)
    {
        if (isset($this->stylesheet)) {
            $this->stylesheet->add($stylesheet);
        } else {
            $this->stylesheet = Stylesheet::stylesheet($stylesheet);
        }

        return $this;
    }

    /**
     * @param string $element
     *
     * @return null|\Relaxsd\Stylesheets\Style
     */
    public function getStyle($element)
    {
        return $this->stylesheet
            ? $this->stylesheet->getStyle($element)
            : null;
    }

}
