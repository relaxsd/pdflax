<?php

namespace Relaxsd\Pdflax;

use Relaxsd\Pdflax\Contracts\PdfDocumentInterface;
use Relaxsd\Pdflax\Fpdf\Translators\Multiline;
use Relaxsd\Stylesheets\Style;
use Relaxsd\Stylesheets\Stylesheet;

class PdfView implements PdfDocumentInterface
{

    use PdfDOMTrait;
    use PdfStyleTrait;
    use PdfMarginTrait;

    /**
     * @var \Relaxsd\Pdflax\Contracts\PdfDocumentInterface
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
     * @var float|string|null
     */
    protected $w;
    /**
     * @var float|string|null
     */
    protected $h;
    /**
     * @var float|null
     */
    protected $localWidth;
    /**
     * @var float|null
     */
    protected $localHeight;

    /**
     * PdfView constructor.
     *
     * @param \Relaxsd\Pdflax\Contracts\PdfDocumentInterface $pdf
     * @param float|string|null                              $x
     * @param float|string|null                              $y
     * @param float|string|null                              $w
     * @param float|string|null                              $h
     * @param \Relaxsd\Stylesheets\Stylesheet|array|null     $stylesheet
     */
    public function __construct($pdf, $x = null, $y = null, $w = null, $h = null, $stylesheet = [])
    {
        $this->pdf = $pdf;

        // In case percentages were given, relate them to the parent
        // All values may be null and will stay null then.
        $this->x = isset($x) ? $x : $pdf->getCursorX();
        $this->y = isset($y) ? $y : $pdf->getCursorY();
        $this->w = $w; // may be null
        $this->h = $h; // may be null

        // Initialise reference size (always floats or null)
        $this->localWidth  = $this->parseGlobalValue_h($w); // Handles percentages, like '50%"
        $this->localHeight = $this->parseGlobalValue_v($h); // Handles percentages, like '50%"

        // Initialize styles, including the ones pass to this method
        $this->initializeStyles($stylesheet);

    }

    /**
     * @param \Relaxsd\Stylesheets\Stylesheet|array|null $stylesheet
     */
    protected function initializeStyles($stylesheet)
    {
        $this->stylesheet = Stylesheet::scaled(
            $this->pdf->getStylesheet(),
            $this->scale_h(),
            $this->scale_v()
        )->add($stylesheet);
    }

    /**
     * @param float $w
     * @param float $h
     * @param bool  $adjustStyles
     *
     * @return $this
     */
    public function setReferenceSize($w, $h, $adjustStyles = true)
    {
        if ($adjustStyles && $this->stylesheet) {

            $this->stylesheet->scale($w / $this->localWidth, $h / $this->localHeight);

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
     * @return $this
     */
    public function setCursorX($x)
    {
        $this->pdf->setCursorX($this->moveToGlobal_h($x));

        return $this;
    }

    /**
     * Position the 'cursor' at a given Y
     *
     * @param float|string $y Local Y-coordinate
     *
     * @return $this
     */
    public function setCursorY($y)
    {
        $this->pdf->setCursorY($this->moveToGlobal_v($y));

        return $this;
    }

    /**
     * Move the 'cursor' in the X direction
     *
     * @param float|string $d Distance
     *
     * @return $this
     */
    public function moveCursorX($d)
    {
        $this->pdf->moveCursorX($this->scaleToGlobal_h($d));

        return $this;
    }

    /**
     * Move the 'cursor' in the Y direction
     *
     * @param float|string $d Distance
     *
     * @return $this
     */
    public function moveCursorY($d)
    {
        $this->pdf->moveCursorY($this->scaleToGlobal_v($d));

        return $this;
    }

    /**
     * Position the 'cursor' at a given X,Y
     *
     * @param float|string $x Local X-coordinate
     * @param float|string $y Local Y-coordinate
     *
     * @return $this
     */
    public function setCursorXY($x, $y)
    {
        $this->pdf->setCursorXY($this->moveToGlobal_h($x), $this->moveToGlobal_v($y));

        return $this;
    }

    /**
     * @param float|string                          $x
     * @param float|string                          $y
     * @param float|string                          $w
     * @param float|string                          $h
     * @param \Relaxsd\Stylesheets\Style|array|null $style
     *
     * @return $this
     */
    public function rectangle($x, $y, $w, $h, $style = null)
    {
        $style = Style::merged($this->getStyle('rect'), $style);

        $this->pdf->rectangle(
            $this->moveToGlobal_h($x),
            $this->moveToGlobal_v($y),
            $this->scaleToGlobal_h($w),
            $this->scaleToGlobal_v($h),
            $this->scaledStyle($style)
        );

        return $this;
    }

    /**
     * @param float|string                          $x1
     * @param float|string                          $y1
     * @param float|string                          $x2
     * @param float|string                          $y2
     * @param \Relaxsd\Stylesheets\Style|array|null $style
     *
     * @return $this
     */
    public function line($x1, $y1, $x2, $y2, $style = null)
    {
        $style = Style::merged($this->getStyle('line'), $style);

        $this->pdf->line(
            $this->moveToGlobal_h($x1),
            $this->moveToGlobal_v($y1),
            $this->moveToGlobal_h($x2),
            $this->moveToGlobal_v($y2),
            $this->scaledStyle($style)
        );

        return $this;
    }

    /**
     * @param float|string                          $h
     * @param string                                $text
     * @param string                                $link
     * @param \Relaxsd\Stylesheets\Style|array|null $style
     *
     * @return $this
     */
    public function write($h, $text, $link = '', $style = null)
    {
        $this->pdf->write(
            $this->scaleToGlobal_v($h),
            $text,
            $link,
            $this->scaledStyle($style)
        );

        return $this;
    }

    /**
     * @param string                                $file
     * @param float|string                          $x
     * @param float|string                          $y
     * @param float|string                          $w
     * @param float|string                          $h
     * @param string                                $type
     * @param string                                $link
     * @param \Relaxsd\Stylesheets\Style|array|null $style
     *
     *
     * @return $this
     */
    public function image($file, $x, $y, $w, $h, $type = '', $link = '', $style = null)
    {
        $this->pdf->image(
            $file,
            $this->moveToGlobal_h($x),
            $this->moveToGlobal_v($y),
            $this->scaleToGlobal_h($w),
            $this->scaleToGlobal_v($h),
            $type,
            $link,
            $this->scaledStyle($style)
        );

        return $this;
    }

    /**
     * @param float|string|null $localX
     *
     * @return float|null
     */
    protected function moveToGlobal_h($localX)
    {

        if (is_null($localX)) return null;

        // Need to evaluate this now to do the add
        $localX = $this->parseLocalValue_h($localX);

        if ($localX < 0) $localX += $this->localWidth;

        return $this->getX() + $this->scaleToGlobal_h($localX);
    }

    /**
     * @param float|null $localY
     *
     * @return float|null
     */
    protected function moveToGlobal_v($localY)
    {
        if (is_null($localY)) return null;

        // Need to evaluate this now to do the add
        $localY = $this->parseLocalValue_v($localY);

        if ($localY < 0) $localY += $this->localHeight;

        return $this->getY() + $this->scaleToGlobal_v($localY);
    }

    /**
     * @param $globalX
     *
     * @return mixed
     */
    protected function moveToLocal_h($globalX)
    {
        return $this::scale($globalX - $this->getX(), $this->scale_h());
    }

    /**
     * @param $globalY
     *
     * @return mixed
     */
    protected function moveToLocal_v($globalY)
    {
        return $this::scale($globalY - $this->getY(), $this->scale_v());
    }

    /**
     * @param float|string|null $localW
     *
     * @return float|null
     */
    protected function scaleToGlobal_h($localW)
    {
        return $this::scale($this->parseLocalValue_h($localW), 1 / $this->scale_h());
    }

    /**
     * @param float|string|null $localH
     *
     * @return float|null
     */
    protected function scaleToGlobal_v($localH)
    {
        return $this::scale($this->parseLocalValue_v($localH), 1 / $this->scale_v());
    }

    /**
     * @param \Relaxsd\Stylesheets\Style|array|null $style
     *
     * @return \Relaxsd\Stylesheets\Style|array|null
     */
    protected function scaledStyle($style)
    {
        return Style::scaled($style, $this->scale_h(), $this->scale_v());
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
    protected function scale_h()
    {
        $viewWidth = $this->getWidth();

        return $viewWidth ? ($this->localWidth / $viewWidth) : 1;
    }

    /**
     * @return float
     */
    protected function scale_v()
    {
        $viewHeight = $this->getHeight();

        return $viewHeight ? ($this->localHeight / $viewHeight) : 1;
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
        return $this->parseGlobalValue_h($this->x);
    }

    /**
     * Gets this components Y position (in terms of the parent coordinate system)
     *
     * @return float
     */
    public function getY()
    {
        // Support for 'n%', relative to parent
        return $this->parseGlobalValue_v($this->y);
    }

    /**
     * Gets this components width (in terms of the parent coordinate system)
     *
     * @return float|null
     */
    public function getWidth()
    {
        // Support for 'n%', relative to parent
        return $this->parseGlobalValue_h($this->w);
    }

    /**
     * Gets this components height (in terms of the parent coordinate system)
     *
     * @return float|null
     */
    public function getHeight()
    {
        // Support for 'n%', relative to parent
        return $this->parseGlobalValue_v($this->h);
    }

    /**
     * @return float
     */
    public function getLocalWidth()
    {
        return $this->localWidth;
    }

    /**
     * @return float
     */
    public function getLocalHeight()
    {
        return $this->localHeight;
    }

    // -----------

    /**
     * @param float|string|null $globalValue
     *
     * @return float|null
     */
    protected function parseGlobalValue_h($globalValue)
    {
        return (is_string($globalValue))
            ? $this->getParentInnerWidth() * floatval($globalValue) / 100
            : $globalValue;
    }

    /**
     * @param float|string|null $globalValue
     *
     * @return float|null
     */
    protected function parseGlobalValue_v($globalValue)
    {
        return (is_string($globalValue))
            ? $this->getParentInnerHeight() * floatval($globalValue) / 100
            : $globalValue;
    }

    /**
     * @param float|string|null $localValue
     *
     * @return float|null
     */
    protected function parseLocalValue_h($localValue)
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
    protected function parseLocalValue_v($localValue)
    {
        return (is_string($localValue))
            ? $this->localHeight * floatval($localValue) / 100
            : $localValue;
    }

    // -----------------

    /**
     * @return float
     */
    protected function getParentInnerWidth()
    {
        return $this->pdf->getWidth() - $this->pdf->getLeftMargin() - $this->pdf->getRightMargin();
    }

    /**
     * @return float
     */
    protected function getParentInnerHeight()
    {
        return $this->pdf->getHeight()- $this->pdf->getTopMargin() - $this->pdf->getBottomMargin();
    }

    /**
     * Get the current X position of the 'cursor' (in the local coordinate system)
     *
     * @return float
     */
    public function getCursorX()
    {
        return $this->moveToLocal_h($this->pdf->GetCursorX());
    }

    /**
     * Get the current Y position of the 'cursor' (in the local coordinate system)
     *
     * @return float
     */
    public function getCursorY()
    {
        return $this->moveToLocal_v($this->pdf->GetCursorY());
    }

    /**
     * @return PdfView
     */
    public function raw()
    {
        return $this->pdf->raw();
    }

    /**
     * @param float|string                          $w
     * @param float|string                          $h
     * @param string                                $txt
     * @param \Relaxsd\Stylesheets\Style|array|null $style
     *
     * @return $this
     */
    public function cell($w, $h = 0.0, $txt = '', $style = null)
    {
        $style = Style::merged($this->getStyle('body'), $this->getStyle('cell'), $style);

        $originalY    = $this->pdf->getCursorY();
        $originalPage = $this->pdf->getPage();

        $this->pdf->cell(
            $this->scaleToGlobal_h($w),
            $this->scaleToGlobal_v($h),
            $txt,
            $this->scaledStyle($style)
        );

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

            // TODO: Fpdf specific.
            // Idea: Use an event for new page?

            // Multiline cells have default ln=2, cell have ln=0
            $default = Multiline::translate($style) ? 2 : 0;
            $ln      = Style::value($style, 'ln', $default);

            if ($ln > 0) {
                $newY -= $this->scaleToGlobal_v($h);
            }

            $this->y += $newY - $originalY;   // Mostly 0, but sometimes a correction of about -266

        }

        return $this;
    }

    /**
     * @param     $auto
     * @param int $margin
     *
     * @return $this
     */
    public function setAutoPageBreak($auto, $margin = 0)
    {
        $this->pdf->setAutoPageBreak($auto, $margin);

        return $this;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setFontPath($path)
    {
        $this->fpdf->setFontPath($path);

        return $this;
    }

    /**
     * @param string  $family
     * @param integer $style
     * @param string  $filename
     *
     * @return $this
     */
    public function registerFont($family, $style, $filename)
    {
        $this->fpdf->registerFont($family, $style, $filename);

        return $this;
    }

    /**
     * @param string|null $orientation
     * @param string|null $size
     *
     * @return $this
     * @throws \Relaxsd\Pdflax\Exceptions\UnsupportedFeatureException
     */
    public function addPage($orientation = null, $size = null)
    {
        $this->pdf->addPage($orientation, $size);

        return $this;
    }

    /**
     * @param string $fileName
     *
     * @return $this
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
     * @param array|null $options
     *
     * @return \Relaxsd\Pdflax\Contracts\CurrencyFormatterInterface
     */
    public function getCurrencyFormatter($options = [])
    {
        return $this->pdf->getCurrencyFormatter($options);
    }

}
