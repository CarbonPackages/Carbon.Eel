<?php

namespace Carbon\Eel\EelHelper;

use Carbon\Eel\Service\MergeClassesService;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use YieldStudio\TailwindMerge\TailwindMerge;

/**
 * @Flow\Proxy(false)
 */
class TailwindHelper implements ProtectedContextAwareInterface
{

    /**
     * Merge multiple Tailwind CSS classes and automatically resolves conflicts between them without headaches.
     *
     * @param iterable|mixed $arguments Optional variable list of arrays / values
     * @return string|null The merged string
     */
    public function merge(...$arguments): ?string
    {
        $mergedString = MergeClassesService::merge(...$arguments);

        if ($mergedString) {
            $twMerge = TailwindMerge::instance();
            return $twMerge->merge($mergedString);
        }

        return null;
    }


    /**
     * All methods are considered safe
     *
     * @param string $methodName The name of the method
     * @return bool
     */
    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
