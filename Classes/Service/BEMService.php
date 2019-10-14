<?php

namespace Carbon\Eel\Service;

class BEMService
{
    /**
     * Generate the array for the modifiers
     * 
     * @param string|array $modifiers
     * 
     * @return array
     */
    private static function modifierArray($modifiers = []): array
    {
        if (is_string($modifiers)) {
            return [$modifiers];
        } else if (is_int($modifiers)) {
            return [strval($modifiers)];
        }
        $array = [];
        if (is_array($modifiers)) {
            foreach ($modifiers as $key => $value) {
                if (!$value && !is_int($value)) {
                    continue;
                }
                if (is_array($value)) {
                    $array = array_merge($array, self::modifierArray($value));
                } else if (is_int($value)) {
                    $array[] = strval($value);
                } else if (is_string($value)) {
                    $array[] = $value;
                } else if (is_string($key)) {
                    $array[] = $key;
                }
            }
        }
        return array_unique($array);
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
    public static function getClassNamesArray($block = null, $element = null, $modifiers = []): array
    {
        if (!isset($block) || !is_string($block) || !$block) {
            return [];
        }
        $baseClass = $element ? "{$block}__{$element}" : "{$block}";
        $classes = [$baseClass];

        if (isset($modifiers)) {
            $modifiers = self::modifierArray($modifiers);
            foreach ($modifiers as $value) {
                $classes[] = "{$baseClass}--{$value}";
            }
        }

        return $classes;
    }

    /**
     * Generates a BEM string
     *
     * @param string       $block     defaults to null
     * @param string       $element   defaults to null
     * @param string|array $modifiers defaults to []
     * 
     * @return string
     */
    public static function getClassNamesString($block = null, $element = null, $modifiers = []): string
    {
        return implode(" ", self::getClassNamesArray($block, $element, $modifiers));
    }
}
