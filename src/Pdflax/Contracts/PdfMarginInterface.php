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

    /**
     * @param $leftMargin
     *
     * @return $this
     */
    public function setRightMargin($leftMargin);

    /**
     * @return float
     */
    public function getRightMargin();

}
