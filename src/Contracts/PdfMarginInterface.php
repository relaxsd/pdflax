<?php

namespace Relaxsd\Pdflax\Contracts;

interface PdfMarginInterface
{

    /**
     * @param $leftMargin
     *
     * @return self
     */
    public function setLeftMargin($leftMargin);

    /**
     * @return float
     */
    public function getLeftMargin();

    /**
     * @param $rightMargin
     *
     * @return self
     */
    public function setRightMargin($rightMargin);

    /**
     * @return float
     */
    public function getRightMargin();

}
