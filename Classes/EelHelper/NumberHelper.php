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
    public function format(float $number, ?int $decimals = 0, ?string $dec_point = ".", ?string $thousands_sep = ","): string
    {
        return number_format($number, $decimals, $dec_point, $thousands_sep);
    }

    /**
     * Format a localized number with grouped thousands
     *
     * @param float $number
     * @param integer|null $decimals
     * @param string|null $locale
     * @return string
     */
    public function formatLocale(float $number, ?int $decimals = 0, ?string $locale = null): string
    {
        if ($locale === null) {
            $locale = $this->localizationService->getConfiguration()->getCurrentLocale();
        }
        setlocale(LC_NUMERIC, $locale);
        $conf = localeconv();
        return number_format($number, $decimals, $conf['decimal_point'], $conf['thousands_sep']);
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
