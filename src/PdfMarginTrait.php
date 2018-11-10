<?php

namespace Relaxsd\Pdflax;

/**
 * Class PdfMarginTrait
 */
trait PdfMarginTrait
{

    /**
     * @var float
     */
    protected $leftMargin = 0;

    /**
     * @var float
     */
    protected $rightMargin = 0;

    /**
     * @return float
     */
    public function getLeftMargin()
    {
        return $this->leftMargin;
    }

    /**
     * @param $leftMargin
     *
     * @return $this
     */
    public function setLeftMargin($leftMargin)
    {
        $this->leftMargin = $leftMargin;

        return $this;
    }

    /**
     * @return float
     */
    public function getRightMargin()
    {
        return $this->rightMargin;
    }

    /**
     * @param $rightMargin
     *
     * @return $this
     */
    public function setRightMargin($rightMargin)
    {
        $this->rightMargin = $rightMargin;

        return $this;
    }

}
