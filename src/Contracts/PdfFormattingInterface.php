<?php

namespace Relaxsd\Pdflax\Contracts;

interface PdfFormattingInterface
{

    /**
     * @param array|null $options
     *
     * @return \Relaxsd\Pdflax\Contracts\CurrencyFormatterInterface
     */
    public function getCurrencyFormatter($options = []);

}
