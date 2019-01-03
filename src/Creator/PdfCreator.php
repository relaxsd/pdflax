<?php

namespace Relaxsd\Pdflax\Creator;

use Relaxsd\Pdflax\Contracts\PdfCreatorInterface;
use Relaxsd\Pdflax\Contracts\PdfCreatorOptionsInterface;
use Relaxsd\Pdflax\Contracts\PdfDocumentInterface;

abstract class PdfCreator implements PdfCreatorInterface, PdfCreatorOptionsInterface
{

    use HasPdfCreatorOptions;

    /**
     * Create a PDF document, using the given options.
     *
     * This object implements PdfCreatorOptionsInterface, so the options given here
     * will be merged with the earlier given options.
     *
     * @param array $options
     *
     * @return PdfDocumentInterface
     * @throws \Relaxsd\Pdflax\Creator\PdfCreatorException
     */
    public abstract function create($options = []);

}
