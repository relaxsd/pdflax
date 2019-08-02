<?php

namespace Relaxsd\Pdflax;

use Relaxsd\Stylesheets\Stylesheet;

/**
 * Trait TextFilterTrait
 *
 * @package Relaxsd\Pdflax
 */
trait TextFilterTrait
{

    /**
     * @var \Relaxsd\Pdflax\Contracts\TextFilterInterface[]
     */
    protected $textFilters = [];

    public function addTextFilter($textFilter)
    {
        $this->textFilters[] = $textFilter;
    }

    protected function applyTextFilters($txt)
    {
        foreach ($this->textFilters as $textFilter) {
            $txt = $textFilter->filter($txt);
        }
        return $txt;
    }
    
}
