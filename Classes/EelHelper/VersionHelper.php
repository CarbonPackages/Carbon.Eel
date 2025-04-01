<?php

namespace Carbon\Eel\EelHelper;

use Neos\Eel\ProtectedContextAwareInterface;

class VersionHelper implements ProtectedContextAwareInterface
{

    /**
     * Returns `true` if the Flow version is lower than 9.
     *
     * @return bool
     */
    public function lowerThanNine(): bool
    {
        return version_compare(FLOW_VERSION_BRANCH, '9.0', 'lt');
    }

    /**
     * Compares the Flow version with the given version.
     *
     * @param string $version version number.
     * @param string|null $operator An optional operator. The possible operators are: <, lt, <=, le, >, gt, >=, ge, ==, =, eq, !=, <>, ne respectively.
     * @return bool|int
     */
    public function compare(string $version, ?string $operator = null)
    {
        if (isset($operator)) {
            if (!in_array($operator, ['<', 'lt', '<=', 'le', '>', 'gt', '>=', 'ge', '==', '=', 'eq', '!=', '<>', 'ne'])) {
                throw new \InvalidArgumentException('Invalid operator: ' . $operator);
            }
        }
        return version_compare(FLOW_VERSION_BRANCH, $version, $operator);
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
