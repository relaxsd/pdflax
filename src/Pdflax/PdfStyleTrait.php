<?php

namespace Pdflax;

/**
 * Class PdfStyleTrait
 */
trait PdfStyleTrait
{

    // Initialized in subclass and/or in constructor
    // TODO: Cannot define here, see class itself
    // protected $stylesheet = [];

    /**
     * Get all available styles.
     *
     * @return array
     */
    public function getStylesheet()
    {
        return $this->stylesheet;
    }

    /**
     * @param array $stylesheet
     *
     * @return $this
     */
    public function addStylesheet($stylesheet)
    {
        foreach ($stylesheet as $class => $styles) {
            $this->setStyle($class, $styles);
        }

        return $this;
    }

    /**
     * @param string $element
     * @param array  $styles
     * @param bool   $replace
     *
     * @return $this
     */
    protected function setStyle($element, array $styles, $replace = false)
    {
        if (!$replace && array_key_exists($element, $this->stylesheet)) {
            $this->stylesheet[$element] = array_merge($this->stylesheet[$element], $styles);
        } else {
            $this->stylesheet[$element] = $styles;
        }

        return $this;
    }

    /**
     * @param array $style
     *
     * @return $this
     */
    public function applyStyle(array $style)
    {
        // Always merge with DEFAULT style to make sure all fields exist
        $style = $this->getStyle('DEFAULT', $style);

        $this->setFont($style['font-family'], $style['font-style'], $style['font-size']);

        if (array_key_exists('fill-color', $style) && isset($style['fill-color'])) {
            $this->setFillColor($style['fill-color']);
        }

        if (array_key_exists('draw-color', $style) && isset($style['draw-color'])) {
            $this->setDrawColor($style['draw-color']);
        }

        if (array_key_exists('text-color', $style) && isset($style['text-color'])) {
            $this->setTextColor($style['text-color']);
        }

        return $this;
    }

    /**
     * @param array|string $style1 Style(s) to merge.
     * @param null         $styleN
     *
     * @return array
     */
    public function getStyle($style1, $styleN = null)
    {
        $result = [];

        foreach (func_get_args() as $style) {
            $result = array_merge($result, $this->evaluateStyle($style));
        }

        return $result;
    }

    /**
     * @param $style
     *
     * @return array
     */
    protected function evaluateStyle($style)
    {
        // Handle null
        if (is_null($style)) return [];

        // Lookup, e.g. 'p' or 'h1'. Also support 'h1' => 'p' config.
        while (is_string($style)) {
            $style = $this->stylesheet[$style];
        }

        // For numeric arrays, like ['p', ['font-size' => 3]], tear them apart:
        if (array_key_exists(0, $style)) {
            $style = call_user_func_array([$this, 'getStyle'], $style);
        }

        // Return normal style array
        return $style;
    }

}
