<?php

namespace Carbon\Eel\EelHelper;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Locale;
use Neos\Flow\I18n\Service as I18nService;
use Neos\Eel\ProtectedContextAwareInterface;

/**
 * Number helpers for Eel contexts
 */
class NumberHelper implements ProtectedContextAwareInterface
{
    /**
     * @Flow\Inject
     * @var I18nService
     */
    protected $localizationService;

    /**
     * Format a number with grouped thousands
     *
     * @param float $number
     * @param integer|null $decimals
     * @param string|null $dec_point
     * @param string|null $thousands_sep
     * @return string
     */
    public function format(float $number, ?int $decimals = null, ?string $dec_point = ".", ?string $thousands_sep = ","): string
    {
        if ($decimals === null) {
            $decimals = $this->decimalDigits($number);
        }
        return \number_format($number, $decimals, $dec_point, $thousands_sep);
    }

    /**
     * Format a localized number with grouped thousands
     *
     * @param float $number
     * @param integer|null $decimals
     * @param string|null $locale
     * @return string
     */
    public function formatLocale(float $number, ?int $decimals = null, ?string $locale = null): string
    {
        if ($decimals === null) {
            $decimals = $this->decimalDigits($number);
        }
        if ($locale === null) {
            $locale = $this->localizationService->getConfiguration()->getCurrentLocale();
        }
        \setlocale(LC_NUMERIC, $locale);
        $conf = \localeconv();
        return \number_format($number, $decimals, $conf['decimal_point'], $conf['thousands_sep']);
    }

    /**
     * Get number of decimal digits
     *
     * @param float $number
     * @return integer
     */
    public function decimalDigits(float $number): int
    {
        if ((int)$number == $number) {
            return 0;
        } else {
            $dotPos = \strrpos($number, '.');
            $commaPos = \strrpos($number, ',');
            $pos = $dotPos ? $dotPos : $commaPos;
            if ($pos === false) {
                return 0;
            }
            return \strlen($number) - $pos - 1;
        }
    }


    /**
     * All methods are considered safe
     *
     * @param string $methodName The name of the method
     *
     * @return bool
     */
    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
