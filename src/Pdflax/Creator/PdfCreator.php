<?php

namespace Pdflax\Creator;

use Pdflax\Contracts\PdfCreatorInterface;
use Pdflax\Contracts\PdfCreatorOptionsInterface;
use Pdflax\Contracts\PdfDocumentInterface;

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
     * @throws \Pdflax\Creator\PdfCreatorException
     */
    public abstract function create($options = []);

}
