<?php

namespace Pdflax\Contracts;

interface PdfMarginInterface
{

    /**
     * @param $leftMargin
     *
     * @return $this
     */
    public function setLeftMargin($leftMargin);

    /**
     * @return float
     */
    public function getLeftMargin();

}
