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

    /**
     * @param $topMargin
     *
     * @return self
     */
    public function setTopMargin($topMargin);

    /**
     * @return float
     */
    public function getTopMargin();

    /**
     * @param $bottomMargin
     *
     * @return self
     */
    public function setBottomMargin($bottomMargin);

    /**
     * @return float
     */
    public function getBottomMargin();

}
