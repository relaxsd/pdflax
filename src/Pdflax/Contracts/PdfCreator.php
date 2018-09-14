<?php

namespace Pdflax\Contracts;

interface PdfCreator
{

    const ORIENTATION_PORTRAIT = 'portrait';
    const ORIENTATION_LANDSCAPE = 'landscape';

    const UNIT_PT = 'pt';
    const UNIT_MM = 'mm';
    const UNIT_CM = 'cm';
    const UNIT_INCH = 'inch';

    const SIZE_A4 = 'A4';

    /**
     * Create a PDF document
     *
     * @param array|null $options
     *
     * @return PdfDocumentInterface
     */
    public function create($options = []);

}
