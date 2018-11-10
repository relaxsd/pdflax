<?php

namespace Pdflax\Style;

class Stylesheet
{

    /** @var Styles[] */
    protected $elementStyles = [];

    /**
     * @param string                    $element
     * @param \Pdflax\Style\Styles|null $styles
     *
     * @return \Pdflax\Style\Stylesheet
     */
    public function addStyles($element, $styles = null)
    {
        $styles = is_null($styles)
            ? new Styles($this)
            : $styles->copy($this);

        if ($existingStyles = $this->getStyles($element)) {
            $existingStyles->mergeStyles($styles);
        } else {
            $this->elementStyles[$element] = $styles;
        }

        return $this;
    }

    /**
     * @param string $element
     * @param string $name
     * @param mixed  $value
     *
     * @return \Pdflax\Style\Stylesheet
     */
    public function addStyle($element, $name, $value)
    {
        $existingStyles = $this->getStyles($element);

        if (!$existingStyles) {
            $this->elementStyles[$element] = ($existingStyles = new Styles($this));
        }

        $existingStyles->add($name, $value);

        return $this;
    }

    /**
     * Gets all elements names that have styles
     *
     * @return string[]
     */
    public function getElements()
    {
        return array_keys($this->elementStyles);
    }

    /**
     * @param string $element
     *
     * @return null|\Pdflax\Style\Styles
     */
    public function getStyles($element)
    {
        return array_key_exists($element, $this->elementStyles)
            ? $this->elementStyles[$element]
            : null;
    }

    /**
     * @param Stylesheet      $stylesheet
     * @param Stylesheet|null $_
     *
     * @return \Pdflax\Style\Stylesheet
     */
    public function mergeStylesheets($stylesheet, $_ = null)
    {
        /** @var \Pdflax\Style\Stylesheet[] $stylesheets */
        $stylesheets = func_get_args();

        foreach ($stylesheets as $stylesheet) {
            $elements = $stylesheet->getElements();
            foreach ($elements as $element) {
                $this->addStyles($element, $stylesheet->getStyles($element));
            }
        }
        return $this;
    }

    /**
     * @param Stylesheet      $stylesheet
     * @param Stylesheet|null $_
     *
     * @return \Pdflax\Style\Stylesheet
     */
    public static function merge($stylesheet, $_ = null)
    {
        return call_user_func_array([new Stylesheet(), "mergeStylesheets"], func_get_args());
    }

    /**
     * @param float $factorH
     * @param float $factorV
     */
    public function scale($factorH, $factorV)
    {
        foreach ($this->elementStyles as $styles) {
            $styles->scale($factorH, $factorV);
        }
    }

}
