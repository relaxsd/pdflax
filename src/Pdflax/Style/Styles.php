<?php

namespace Pdflax\Style;

class Styles
{

    /**
     * The stylesheet that contains this style
     *
     * @var \Pdflax\Style\Stylesheet
     */
    protected $stylesheet;

    /** @var mixed[] */
    protected $styles = [];

    /** @var string[] */
    protected $ancestors = [];

    /**
     * Styles constructor.
     *
     * @param \Pdflax\Style\Stylesheet|null $stylesheet
     */
    public function __construct($stylesheet = null)
    {
        $this->stylesheet = $stylesheet;
    }

    /**
     * Merge styles from another Styles object into this object.
     * This does not copy the inheritance!
     *
     * @param Styles|null $styles
     *
     * @return $this
     */
    public function mergeStyles($styles)
    {
        if ($styles) {
            foreach ($styles->styles as $name => $value) {
                $this->add($name, $value);
            }
        }

        return $this;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    public function add($name, $value)
    {
        $this->styles[$name] = $value;

        return $this;
    }

    /**
     * Returns the value for a given attribute (e.g. 'border').
     * If this collection extends from other elements, those are also searched (deep).
     *
     * @param string $attribute
     *
     * @return null|mixed The value or null if not found.
     */
    public function getValue($attribute)
    {
        return array_key_exists($attribute, $this->styles)
            ? $this->styles[$attribute]
            : $this->getInheritedValue($attribute);
    }

    /**
     * Returns all values for this Styles object.
     *
     * @param bool $withInherited
     *
     * @return mixed[]
     */
    public function getValues($withInherited = true)
    {
        if ($withInherited) {

            $inheritedStyles = new Styles($this->stylesheet);
            foreach ($this->ancestors as $anchestor) {
                $inheritedStyles->mergeStyles($this->stylesheet->getStyles($anchestor));
            }

            $inheritedStyles->mergeStyles($this);

            return $inheritedStyles->styles;

        }

        return $this->styles;

    }

    /**
     * Searches the ancestor(s) for the Style of a given attribute.
     *
     * @param string $attribute
     *
     * @return null|mixed The value or null if not found.
     */
    protected function getInheritedValue($attribute)
    {
        foreach ($this->ancestors as $anchestor) {
            $styles = $this->stylesheet->getStyles($anchestor);

            if ($styles && ($result = $styles->getValue($attribute))) {
                return $result;
            }
        }
        return null;
    }

    /**
     * @return \Pdflax\Style\Stylesheet
     */
    public function getStylesheet()
    {
        return $this->stylesheet;
    }

    /**
     * @param \Pdflax\Style\Stylesheet $stylesheet
     *
     * @return Styles
     */
    public function setStylesheet($stylesheet)
    {
        $this->stylesheet = $stylesheet;
        return $this;
    }

    /**
     * @param array|string $element
     * @param string       $_
     *
     * @return \Pdflax\Style\Styles
     */
    public function extendsFrom($element, $_ = null)
    {
        $elements = is_array($element)
            ? $element
            : func_get_args();

        foreach ($elements as $element) {
            if (!$this->isDescendantOf($element)) {
                $this->ancestors[] = $element;
            }
        }

        return $this;
    }

    public function isDescendantOf($element)
    {
        return in_array($element, $this->ancestors);
    }

    /**
     * Copy this Styles object and parent to a stylesheet
     *
     * @param \Pdflax\Style\Stylesheet $stylesheet
     *
     * @return \Pdflax\Style\Styles
     */
    public function copy($stylesheet)
    {
        $clone = clone $this;
        $clone->setStylesheet($stylesheet);
        return $clone;
    }

    /**
     * @param float $factor
     *
     * @return \Pdflax\Style\Styles
     */
    public function scale($factor = 1.0)
    {
        foreach ($this->styles as $attribute => &$value) {
            if ($attribute == 'size' || self::endsWith($attribute, '-size')) {
                $value *= $factor;
            }
        }
        return $this;
    }

    private static function endsWith($haystack, $needle)
    {
        return ((string) $needle === substr($haystack, -strlen($needle)));
    }

}
