<?php

namespace Pdflax\Contracts;

interface PdfCreator
{

    /**
     * Create a PDF document
     *
     * @param array|null $data
     *
     * @return PdfDocumentInterface
     */
    public function create($data = []);

}
