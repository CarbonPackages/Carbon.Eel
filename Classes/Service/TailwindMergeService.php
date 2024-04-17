<?php

namespace Carbon\Eel\Service;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cache\CacheManager;
use TailwindMerge\TailwindMerge;
use TailwindMerge\Validators\AnyValueValidator;
use TailwindMerge\Validators\ArbitraryImageValidator;
use TailwindMerge\Validators\ArbitraryLengthValidator;
use TailwindMerge\Validators\ArbitraryNumberValidator;
use TailwindMerge\Validators\ArbitraryPositionValidator;
use TailwindMerge\Validators\ArbitraryShadowValidator;
use TailwindMerge\Validators\ArbitrarySizeValidator;
use TailwindMerge\Validators\ArbitraryValueValidator;
use TailwindMerge\Validators\IntegerValidator;
use TailwindMerge\Validators\LengthValidator;
use TailwindMerge\Validators\NumberValidator;
use TailwindMerge\Validators\PercentValidator;
use TailwindMerge\Validators\TshirtSizeValidator;
use function is_array;

class TailwindMergeService
{
    /**
     * @var array
     * @Flow\InjectConfiguration(path="tailwindMergeConfig")
     */
    protected $mergeConfig;

    /**
     * @var CacheManager
     */
    protected $flowCacheManager;

    /**
     * @param CacheManager $flowCacheManager
     */
    public function __construct(CacheManager $flowCacheManager)
    {
        $this->flowCacheManager = $flowCacheManager;
    }

    protected $instance;

    /**
     * Merge the given classes
     *
     * @param iterable|mixed $arguments Optional variable list of arrays / values
     * @return string|null The merged classes
     */
    public function merge(...$arguments): ?string
    {
        if (!$this->instance) {
            $this->instance = $this->createInstance();
        }

        return $this->instance->merge(...$arguments);
    }

    /**
     * Create a new TailwindMerge instance
     *
     * @return TailwindMerge
     */
    protected function createInstance(): TailwindMerge {
        $cache = $this->flowCacheManager->getSimpleCache('Carbon_Eel_Tailwind');
        if (isset($this->mergeConfig) && is_array($this->mergeConfig)) {
            return TailwindMerge::factory()->withConfiguration($this->parseConfig($this->mergeConfig))->withCache($cache)->make();
        }

        return TailwindMerge::factory()->withCache($cache)->make();
    }

    /**
     * Merge the configuration and replace the constants with the actual validators
     *
     * @param iterable|mixed $config The configuration
     * @return mixed
     */
    protected function parseConfig($config) {
        if (is_array($config)) {
            $parsedConfig = [];
            foreach ($config as $key => $value) {
                $parsedConfig[$key] = $this->parseConfig($value);
            }
            return $parsedConfig;
        }

        switch ($config) {
            case 'ANY_VALUE_VALIDATOR':
                return AnyValueValidator::validate(...);
            case 'ARBITRARY_IMAGE_VALIDATOR':
                return ArbitraryImageValidator::validate(...);
            case 'ARBITRARY_LENGTH_VALIDATOR':
                return ArbitraryLengthValidator::validate(...);
            case 'ARBITRARY_NUMBER_VALIDATOR':
                return ArbitraryNumberValidator::validate(...);
            case 'ARBITRARY_POSITION_VALIDATOR':
                return ArbitraryPositionValidator::validate(...);
            case 'ARBITRARY_SHADOW_VALIDATOR':
                return ArbitraryShadowValidator::validate(...);
            case 'ARBITRARY_SIZE_VALIDATOR':
                return ArbitrarySizeValidator::validate(...);
            case 'ARBITRARY_VALUE_VALIDATOR':
                return ArbitraryValueValidator::validate(...);
            case 'INTEGER_VALIDATOR':
                return IntegerValidator::validate(...);
            case 'LENGTH_VALIDATOR':
                return LengthValidator::validate(...);
            case 'NUMBER_VALIDATOR':
                return NumberValidator::validate(...);
            case 'PERCENT_VALIDATOR':
                return PercentValidator::validate(...);
            case 'TSHIRT_SIZE_VALIDATOR':
                return TshirtSizeValidator::validate(...);
        }

        return $config;
    }
}
