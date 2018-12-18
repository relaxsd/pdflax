<?php

namespace Relaxsd\Pdflax;

/**
 * Class PdfMarginTrait
 */
trait PdfMarginTrait
{

    /** @var float */
    protected $leftMargin = 0.0;

    /** @var float */
    protected $rightMargin = 0.0;

    /** @var float */
    protected $topMargin = 0.0;

    /** @var float */
    protected $bottomMargin = 0.0;

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

    /**
     * @return float
     */
    public function getTopMargin()
    {
        return $this->topMargin;
    }

    /**
     * @param $topMargin
     *
     * @return $this
     */
    public function setTopMargin($topMargin)
    {
        $this->topMargin = $topMargin;

        return $this;
    }

    /**
     * @return float
     */
    public function getBottomMargin()
    {
        return $this->bottomMargin;
    }

    /**
     * @param $bottomMargin
     *
     * @return $this
     */
    public function setBottomMargin($bottomMargin)
    {
        $this->bottomMargin = $bottomMargin;

        return $this;
    }

    // ==================================================

    /**
     * Get the inner width of this document, between its left and right margins
     *
     * @return float
     */
    public function getInnerWidth()
    {
        return $this->getWidth() - $this->getLeftMargin() - $this->getRightMargin();
    }

    /**
     * Get the inner height of this document, between its top and bottom margins
     *
     * @return float
     */
    public function getInnerHeight()
    {
        return $this->getHeight() - $this->getTopMargin() - $this->getBottomMargin();
    }

}
