<?php

namespace Pdflax\Contracts;

interface PdfFormattingInterface
{

    /**
     * @param array|null $options
     *
     * @return \Pdflax\Contracts\CurrencyFormatterInterface
     */
    public function getCurrencyFormatter($options = []);

}
