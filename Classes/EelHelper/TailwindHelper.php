<?php

namespace Carbon\Eel\EelHelper;

use Carbon\Eel\Service\MergeClassesService;
use Carbon\Eel\Service\TailwindMergeService;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;

class TailwindHelper implements ProtectedContextAwareInterface
{
    /**
     * @var TailwindMergeService
     * @Flow\Inject
     */
    protected $mergeService;

    /**
     * Merge multiple Tailwind CSS classes and automatically resolves conflicts between them without headaches.
     *
     * @param iterable|mixed $arguments Optional variable list of arrays / values
     * @return string|null The merged string
     */
    public function merge(...$arguments): ?string
    {
        $mergedString = MergeClassesService::merge(...$arguments);
        return $mergedString ? $this->mergeService->merge($mergedString) : null;
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
