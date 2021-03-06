<?php

namespace Relaxsd\Pdflax\Contracts;

/**
 * Interface PdfCreatorInterface
 *
 * A PdfCreatorInterface creates a PDF Document (an instance of Relaxsd\Pdflax\Contracts\PdfDocumentInterface).
 *
 * @package Relaxsd\Pdflax\Contracts
 * @see     \Relaxsd\Pdflax\Contracts\PdfDocumentInterface
 */
interface PdfCreatorInterface
{

    /**
     * Create a PDF document, using the given options.
     *
     * Some factories allow configuration of options beforehand, e.g. with setOption() or pageSizeA4(),
     * in that case the options given here will be merged with the earlier given options.
     *
     * @param array $options
     *
     * @return PdfDocumentInterface
     * @throws \Relaxsd\Pdflax\Creator\PdfCreatorException
     */
    public function create($options = []);

}
