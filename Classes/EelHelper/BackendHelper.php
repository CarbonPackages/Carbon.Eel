<?php

namespace Carbon\Eel\EelHelper;

use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Exception;
use Neos\Flow\I18n\EelHelper\TranslationHelper;
use Neos\Neos\Service\UserService;

class BackendHelper implements ProtectedContextAwareInterface
{

    const I18N_LABEL_ID_PATTERN = '/^[a-z0-9]+\.(?:[a-z0-9][\.a-z0-9]*)+:[a-z0-9.]+:.+$/i';

    /**
     * @Flow\Inject
     * @var UserService
     */
    protected $userService;

    /**
     * @Flow\Inject
     * @var TranslationHelper
     */
    protected $translationHelper;

    /**
     * Returns the language from the interface
     *
     * @return string
     */
    public function language(): string
    {
        return $this->userService->getInterfaceLanguage();
    }

    /**
     * Get the translated value for an id or original label in the interface language
     *
     * @param string $id
     * @param string|null $originalLabel
     * @param array $arguments
     * @param string $source
     * @param string|null $package
     * @param integer|null $quantity
     * @param string|null $locale
     * @return string
     * @throws Exception
     */
    public function translate($id, $originalLabel = null, array $arguments = [], $source = 'Main', $package = null, $quantity = null, $locale = null): string
    {
        if ($locale === null) {
            $locale = $this->userService->getInterfaceLanguage();
        }

        if (
            $originalLabel === null &&
            $arguments === [] &&
            $source === 'Main' &&
            $package === null &&
            $quantity === null
        ) {
            return preg_match(self::I18N_LABEL_ID_PATTERN, $id) === 1 ? $this->translateByShortHandString($id, $locale) : $id;
        }

        return $this->translationHelper->translate($id, $originalLabel, $arguments, $source, $package, $quantity, $locale);
    }

    /**
     * @param string $shortHandString
     * @return string
     * @throws Exception
     */
    protected function translateByShortHandString($shortHandString, $locale)
    {
        $shortHandStringParts = explode(':', $shortHandString);
        if (count($shortHandStringParts) === 3) {
            list($package, $source, $id) = $shortHandStringParts;
            return $this->translationHelper->translate($id, null, [], $source, $package, null, $locale);
        }

        throw new \InvalidArgumentException(sprintf('The translation shorthand string "%s" has the wrong format', $shortHandString), 1613464966);
    }

    /**
     * All methods are considered safe
     *
     * @param string $methodName
     * @return boolean
     */
    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
