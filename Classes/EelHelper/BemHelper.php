<?php

namespace Carbon\Eel\EelHelper;

use Neos\Flow\Annotations as Flow;
use Neos\Eel\ProtectedContextAwareInterface;
use Carbon\Eel\Service\BEMService;

/**
 * @Flow\Proxy(false)
 */
class BemHelper implements ProtectedContextAwareInterface
{
    /**
     * Generates a BEM string
     *
     * @param string       $block     defaults to null
     * @param string       $element   defaults to null
     * @param string|array $modifiers defaults to []
     *
     * @return string
     */
    public function string($block = null, $element = null, $modifiers = []): string
    {
        return BEMService::getClassNamesString($block, $element, $modifiers);
    }

    /**
     * Generates a BEM modifier string
     *
     * @param string       $class     defaults to null
     * @param string|array $modifiers defaults to []
     *
     * @return string
     */
    public function modifier($class = null, $modifiers = []): string
    {
        return BEMService::getClassNamesString($class, null, $modifiers);
    }

    /**
     * Generates a BEM array
     *
     * @param string       $block     defaults to null
     * @param string       $element   defaults to null
     * @param string|array $modifiers defaults to []
     *
     * @return array
     */
    public function array($block = null, $element = null, $modifiers = []): array
    {
        return BEMService::getClassNamesArray($block, $element, $modifiers);
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
