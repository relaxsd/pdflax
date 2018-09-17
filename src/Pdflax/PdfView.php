<?php

namespace Pdflax;

use Pdflax\Contracts\PdfDocumentInterface;

class PdfView implements PdfDocumentInterface
{

    use PdfDOMTrait;
    use PdfStyleTrait;
    use PdfMarginTrait;

    // These will be merged with parent document styles (scaled, see FpdfView constructor)
    // Subclasses can define their own styles to be merged.
    protected $stylesheet = [];

    /**
     * @var \Pdflax\Contracts\PdfDocumentInterface
     */
    protected $pdf;
    /**
     * @var float|string
     */
    protected $x;
    /**
     * @var float|string
     */
    protected $y;
    /**
     * @var float|string
     */
    protected $w;
    /**
     * @var float|string
     */
    protected $h;
    /**
     * @var float
     */
    protected $localWidth;
    /**
     * @var float
     */
    protected $localHeight;

    /**
     * PdfScaler constructor.
     *
     * @param \Pdflax\Contracts\PdfDocumentInterface $pdf
     * @param float|string                           $x
     * @param float|string                           $y
     * @param float|string                           $w
     * @param float|string                           $h
     */
    public function __construct($pdf, $x, $y, $w, $h)
    {
        $this->pdf = $pdf;

        // In case percentages were given, relate them to the parent
        // All values may be null and will stay null then.
        $this->x = $x;
        $this->y = $y;
        $this->w = $w;
        $this->h = $h;

        // Initialise reference size (always floats)
        $this->localWidth  = $this->eval_parent_h($w);
        $this->localHeight = $this->eval_parent_v($h);

        // Initialize styles, including the ones pass to this method
        $this->initializeStyles();

    }

    protected function initializeStyles()
    {
        // Get styles from parent...
        $mergedStylesheet = $this->pdf->getStylesheet();

        // Transpose them to local measurements...
        array_walk($mergedStylesheet, function (&$elementStyles) {
            $elementStyles = $this->toLocalStyle($elementStyles);
        });

        // Merge $this->stylesheet (may be set by subclass)
        $mergedStylesheet = $this->mergeStylesheets($mergedStylesheet, $this->stylesheet);

        // Store them
        $this->stylesheet = $mergedStylesheet;
    }

    /**
     * @param float $w
     * @param float $h
     * @param bool  $adjustStyles
     *
     * @return self
     */
    public function setReferenceSize($w, $h, $adjustStyles = true)
    {
        if ($adjustStyles) {

            array_walk($this->stylesheet, function (&$elementStyles) use ($w, $h){
                $elementStyles = $this->scaleStyle($elementStyles, $w / $this->localWidth, $h / $this->localHeight);
            });

        }

        $this->localWidth  = $w;
        $this->localHeight = $h;

        return $this;
    }

    /**
     * Position the 'cursor' at a given X
     *
     * @param float|string $x Local X-coordinate
     *
     * @return self
     */
    public function setCursorX($x)
    {
        $this->pdf->setCursorX($this->moveToGlobal_horz($x));

        return $this;
    }

    /**
     * Position the 'cursor' at a given Y
     *
     * @param float|string $y Local Y-coordinate
     *
     * @return self
     */
    public function setCursorY($y)
    {
        $this->pdf->setCursorY($this->moveToGlobal_vert($y));

        return $this;
    }

    /**
     * Move the 'cursor' in the Y direction
     *
     * @param float|string $d Distance
     *
     * @return self
     */
    public function moveCursorY($d)
    {
        $this->pdf->setCursorY($this->getCursorY() + $this->moveToGlobal_vert($d));

        return $this;
    }

    /**
     * Position the 'cursor' at a given X,Y
     *
     * @param float|string $x Local X-coordinate
     * @param float|string $y Local Y-coordinate
     *
     * @return self
     */
    public function setCursorXY($x, $y)
    {
        $this->pdf->setCursorXY($this->moveToGlobal_horz($x), $this->moveToGlobal_vert($y));

        return $this;
    }

    /**
     * @param float|string $x
     * @param float|string $y
     * @param float|string $w
     * @param float|string $h
     * @param string       $style
     *
     * @return self
     */
    public function rectangle($x, $y, $w, $h, $style = '')
    {
        $this->pdf->rectangle($this->moveToGlobal_horz($x), $this->moveToGlobal_vert($y), $this->scaleToGlobal_horz($w), $this->scaleToGlobal_vert($h), $style);

        return $this;
    }

    /**
     * @param string $family
     * @param int    $style
     * @param int    $size
     *
     * @return self
     */
    public function setFont($family, $style = self::FONT_STYLE_NORMAL, $size = 0)
    {
        $this->pdf->setFont($family, $style, $this->scaleToGlobal_horz($size));

        return $this;
    }

    /**
     * @param float|string $x1
     * @param float|string $y1
     * @param float|string $x2
     * @param float|string $y2
     * @param array|string $style
     *
     * @return self
     */
    public function line($x1, $y1, $x2, $y2, $style = '')
    {
        $this->pdf->line($this->moveToGlobal_horz($x1), $this->moveToGlobal_vert($y1), $this->moveToGlobal_horz($x2), $this->moveToGlobal_vert($y2), $style);

        return $this;
    }

    /**
     * @param float|string $h
     * @param string       $text
     * @param string       $link
     *
     * @return self
     */
    public function write($h, $text, $link = '')
    {
        $this->pdf->write($this->scaleToGlobal_vert($h), $text, $link);

        return $this;
    }

    /**
     * @param string       $file
     * @param float|string $x
     * @param float|string $y
     * @param float|string $w
     * @param float|string $h
     * @param string       $type
     * @param string       $link
     *
     * @return self
     */
    public function image($file, $x, $y, $w, $h, $type = '', $link = '')
    {
        $this->pdf->image($file, $this->moveToGlobal_horz($x), $this->moveToGlobal_vert($y), $this->scaleToGlobal_horz($w), $this->scaleToGlobal_vert($h), $type, $link);

        return $this;
    }

    /**
     * @param float|string|null $localX
     *
     * @return float|null
     */
    protected function moveToGlobal_horz($localX)
    {

        if (is_null($localX)) return null;

        // Need to evaluate this now to do the add
        $localX = $this->eval_view_horz($localX);

        if ($localX < 0) $localX += $this->localWidth;

        return $this->getX() + $this->scaleToGlobal_horz($localX);
    }

    /**
     * @param float|null $localY
     *
     * @return float|null
     */
    protected function moveToGlobal_vert($localY)
    {
        if (is_null($localY)) return null;

        // Need to evaluate this now to do the add
        $localY = $this->eval_view_vert($localY);

        if ($localY < 0) $localY += $this->localHeight;

        return $this->getY() + $this->scaleToGlobal_vert($localY);
    }

    /**
     * @param $globalX
     *
     * @return mixed
     */
    protected function moveToLocal_horz($globalX)
    {
        return self::scale($globalX - $this->getX(), $this->scale_horz());
    }

    /**
     * @param $globalY
     *
     * @return mixed
     */
    protected function moveToLocal_vert($globalY)
    {
        return self::scale($globalY - $this->getY(), $this->scale_vert());
    }

    /**
     * @param float|string|null $localW
     *
     * @return float|null
     */
    protected function scaleToGlobal_horz($localW)
    {
        return self::scale($this->eval_view_horz($localW), 1 / $this->scale_horz());
    }

    /**
     * @param float|string|null $localH
     *
     * @return float|null
     */
    protected function scaleToGlobal_vert($localH)
    {
        return self::scale($this->eval_view_vert($localH), 1 / $this->scale_vert());
    }

    /**
     * @param float|string|null $globalW
     *
     * @return float|null
     */
    protected function scaleToLocal_horz($globalW)
    {
        // TODO: No support for 'n%' like scaleToGlobal_horz?
        return self::scale($globalW, $this->scale_horz());
    }

    /**
     * @param float|string|null $globalH
     *
     * @return float|null
     */
    protected function scaleToLocal_vert($globalH)
    {
        // TODO: No support for 'n%' like scaleToGlobal_vert?
        return self::scale($globalH, $this->scale_vert());
    }

    /**
     * @param float|null $value
     * @param float      $factor
     *
     * @return float|null
     */
    protected static function scale($value, $factor)
    {
        if (is_null($value)) return null;

        return $value * $factor;
    }

    /**
     * @return float
     */
    protected function scale_horz()
    {
        $viewWidth = $this->getWidth();

        return $viewWidth ? ($this->localWidth / $viewWidth) : 1;
    }

    /**
     * @return float
     */
    protected function scale_vert()
    {
        $viewHeight = $this->getHeight();

        return $viewHeight ? ($this->localHeight / $viewHeight) : 1;
    }

    /**
     * @param array $style
     *
     * @return array
     */
    protected function toGlobalStyle(array $style)
    {
        return self::scaleStyle($style, 1/$this->scale_horz(), 1/$this->scale_vert());
    }

    protected function toLocalStyle(array $style)
    {
        return self::scaleStyle($style, $this->scale_horz(), $this->scale_vert());
    }

    /**
     * @param array $style
     * @param float $factorHorz
     * @param float $factorVert
     *
     * @return array
     */
    protected function scaleStyle(array $style, $factorHorz, $factorVert)
    {
        if (array_key_exists('font-size', $style)) {
            $style['font-size'] = self::scale($style['font-size'], $factorVert);
        }

        if (array_key_exists('border-size', $style)) {
            $style['border-size'] = self::scale($style['border-size'], $factorVert);
        }

        return $style;
    }

    /**
     * Get the current page number.
     *
     * @return int
     */
    public function getPage()
    {
        return $this->pdf->getPage();
    }

    /**
     * Gets this components X position (in terms of the parent coordinate system)
     *
     * @return float
     */
    public function getX()
    {
        // Support for 'n%', relative to parent
        return $this->eval_parent_h($this->x);
    }

    /**
     * Gets this components Y position (in terms of the parent coordinate system)
     *
     * @return float
     */
    public function getY()
    {
        // Support for 'n%', relative to parent
        return $this->eval_parent_v($this->y);
    }

    /**
     * Gets this components width (in terms of the parent coordinate system)
     *
     * @return float
     */
    public function getWidth()
    {
        // Support for 'n%', relative to parent
        return $this->eval_parent_h($this->w);
    }

    /**
     * Gets this components height (in terms of the parent coordinate system)
     *
     * @return float
     */
    public function getHeight()
    {
        // Support for 'n%', relative to parent
        return $this->eval_parent_v($this->h);
    }

    // -----------

    /**
     * @param float|string|null $globalValue
     *
     * @return float|null
     */
    protected function eval_parent_h($globalValue)
    {
        return (is_string($globalValue))
            ? $this->getParentWidth() * floatval($globalValue) / 100
            : $globalValue;
    }

    /**
     * @param float|string|null $globalValue
     *
     * @return float|null
     */
    protected function eval_parent_v($globalValue)
    {
        return (is_string($globalValue))
            ? $this->getParentHeight() * floatval($globalValue) / 100
            : $globalValue;
    }

    /**
     * @param float|string|null $localValue
     *
     * @return float|null
     */
    protected function eval_view_horz($localValue)
    {
        return (is_string($localValue))
            ? $this->localWidth * floatval($localValue) / 100
            : $localValue;
    }

    /**
     * @param float|string|null $localValue
     *
     * @return float|null
     */
    protected function eval_view_vert($localValue)
    {
        return (is_string($localValue))
            ? $this->localHeight * floatval($localValue) / 100
            : $localValue;
    }

    // -----------------

    /**
     * @return float
     */
    protected function getParentWidth()
    {
        return $this->pdf->getWidth();
    }

    /**
     * @return float
     */
    protected function getParentHeight()
    {
        return $this->pdf->getHeight();
    }

    /**
     * Get the current X position of the 'cursor' (in the local coordinate system)
     *
     * @return float
     */
    public function getCursorX()
    {
        return $this->moveToLocal_horz($this->pdf->GetCursorX());
    }

    /**
     * Get the current Y position of the 'cursor' (in the local coordinate system)
     *
     * @return float
     */
    public function getCursorY()
    {
        return $this->moveToLocal_vert($this->pdf->GetCursorY());
    }

    /**
     * @return PdfView
     */
    public function raw()
    {
        return $this->pdf->raw();
    }

    /**
     * @param float $value
     *
     * @return self
     */
    public function euro($value)
    {
        $this->pdf->euro($value);

        return $this;
    }

    /**
     * @param float|string $w
     * @param float|string $h
     * @param string       $txt
     * @param array|string $options
     */
    public function cell($w, $h = 0.0, $txt = '', $options = [])
    {
        // Merge the options with the defaults to be sure all fields exist.
        $options = $this->getStyle('cell', $options);

        $originalY    = $this->pdf->getCursorY();
        $originalPage = $this->pdf->getPage();

        $globalW = $this->scaleToGlobal_horz($w);
        $globalH = $this->scaleToGlobal_vert($h);
        $this->pdf->cell($globalW, $globalH, $txt, $options);

        // After an automatic page-break, the y will move from the coordinate
        // on the old page (e.g. 276) to the coordinate on the new page (e.g. 10)
        // If that's the case, move the entire view
        $newPage = $this->pdf->getPage();

        if ($newPage != $originalPage) {

            // if ($options['multiline']) {
            //     \Log::warning("Encountered a Page Breaks in a MultiCells within PDF view. This is not supported.");
            // }

            // TODO: Maybe use the page size (minus top/bottom margins)? The view moved one page backwards?
            $newY = $this->pdf->getCursorY();

            if ($options['ln'] > 0) {
                $newY -= $globalH;
            }

            $this->y += $newY - $originalY;   // Mostly 0, but sometimes a correction of about -266

        }

    }

    /**
     * @param string|int|array $r Red value (with $g and $b) or greyscale value ($g and $b null) or color name or [r,g,b] array
     * @param int|null         $g Green value
     * @param int|null         $b Blue value
     *
     * @return self
     */
    public function setDrawColor($r, $g = null, $b = null)
    {
        $this->pdf->setDrawColor($r, $g, $b);

        return $this;
    }

    /**
     * @param string|int|array $r Red value (with $g and $b) or greyscale value ($g and $b null) or color name or [r,g,b] array
     * @param int|null         $g Green value
     * @param int|null         $b Blue value
     *
     * @return self
     */
    public function setTextColor($r, $g = null, $b = null)
    {
        $this->pdf->setTextColor($r, $g, $b);

        return $this;
    }

    /**
     * @param string|int|array $r Red value (with $g and $b) or greyscale value ($g and $b null) or color name or [r,g,b] array
     * @param int|null         $g Green value
     * @param int|null         $b Blue value
     *
     * @return self
     */
    public function setFillColor($r, $g = null, $b = null)
    {
        $this->pdf->setFillColor($r, $g, $b);

        return $this;
    }

    /**
     * @param     $auto
     * @param int $margin
     *
     * @return self
     */
    public function setAutoPageBreak($auto, $margin = 0)
    {
        $this->pdf->setAutoPageBreak($auto, $margin);

        return $this;
    }

    /**
     * @param string|null $orientation
     * @param string|null $size
     *
     * @return self
     * @throws \Pdflax\Exceptions\UnsupportedFeatureException
     */
    public function addPage($orientation = null, $size = null)
    {
        $this->pdf->addPage($orientation, $size);

        return $this;
    }

    /**
     * @param string $fileName
     *
     * @return self
     */
    public function save($fileName)
    {
        $this->pdf->save($fileName);

        return $this;
    }

    /**
     * @return string
     */
    public function getPdfContent()
    {
        return $this->pdf->getPdfContent();
    }

    /**
     * @param int        $n
     * @param array|null $options
     *
     * @return mixed
     */
    public function newLine($n = 1, $options = null)
    {
        $this->pdf->newLine($n, $options);

        return $this;
    }

    /**
     * @param              $stylesheet
     * @param array|string $extraStylesheet
     *
     * @return array
     */
    protected function mergeStylesheets($stylesheet, $extraStylesheet)
    {
        // Then merge our styles back in.
        foreach ((array)$extraStylesheet as $elementName => $elementStyle) {
            $stylesheet[$elementName] = array_key_exists($elementName, $stylesheet)
                ? array_merge($stylesheet[$elementName], $elementStyle)
                : $elementStyle;
        }
        return $stylesheet;
    }

}
