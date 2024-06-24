<?php

namespace Mantax559\LaravelBladeAttributeSorter\Services;

class SortAttributesService
{
    private array $attributeOrder;

    public function __construct()
    {
        $this->setAttributeOrder(
            config('laravel-blade-attribute-sorter.default'),
            config('laravel-blade-attribute-sorter.custom'),
        );
    }

    public function setAttributeOrder(?array $default, ?array $custom): array
    {
        if (! empty($default)) {
            $this->attributeOrder['default'] = array_filter($default, fn ($value) => ! empty($value));
        }

        if (! empty($custom)) {
            $this->attributeOrder['custom'] = array_filter($custom, fn ($value) => ! empty($value));
        }

        return $this->attributeOrder;
    }

    public function sortAttributes(string $content): string
    {
        $attributePattern = '/<([a-zA-Z0-9\-:.]+)((?:\s+[a-zA-Z0-9\-:.@]+(?:=(?:"[^"]*"|\'[^\']*\'|{{[^}]*}}|\S+))?)*)\s*(\/?)>/m';

        return preg_replace_callback($attributePattern, function ($matches) {
            $tag = $matches[1];
            $attributes = trim($matches[2]);
            $selfClosing = $matches[3] === '/' ? ' /' : '';
            $attributeMatches = $this->parseAttributes($attributes);
            $sortedAttributes = $this->sortAttributesByOrder($tag, $attributeMatches);

            return "<$tag".($sortedAttributes ? ' '.$sortedAttributes : '').$selfClosing.'>';
        }, $content);
    }

    private function parseAttributes(string $attributes): array
    {
        $attributes = preg_replace('/\s+/', ' ', trim($attributes));
        $attributeMatches = [];

        preg_match_all('/([a-zA-Z0-9\-:.@]+)(?:=("[^"]*"|\'[^\']*\'|{{[^}]*}}|\S+))?/', $attributes, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $name = mb_strtolower($match[1]);
            $value = $match[2] ?? $name;
            $attributeMatches[] = [
                'name' => $name,
                'value' => trim($value, '"\''),
                'attribute' => $name.(isset($match[2]) ? '='.$match[2] : ''),
            ];
        }

        return $attributeMatches;
    }

    private function sortAttributesByOrder(string $tag, array $attributes): string
    {
        $customOrder = $this->attributeOrder['custom'][$tag] ?? [];
        $defaultOrder = $this->attributeOrder['default'];

        $sortedAttributes = [];
        $remainingAttributes = [];

        foreach ($attributes as $attribute) {
            $name = $attribute['name'];
            if ($this->matchesPattern($name, $customOrder)) {
                $sortedAttributes[$name] = trim($attribute['attribute']);
            } else {
                $remainingAttributes[$name] = trim($attribute['attribute']);
            }
        }

        $finalAttributes = $this->mergeOrderedAttributes($sortedAttributes, $customOrder);
        $finalAttributes = array_merge($finalAttributes, $this->mergeOrderedAttributes($remainingAttributes, $defaultOrder));

        asort($remainingAttributes);
        $finalAttributes = array_merge($finalAttributes, $remainingAttributes);

        return implode(' ', $finalAttributes);
    }

    private function mergeOrderedAttributes(array &$attributes, array $order): array
    {
        $finalAttributes = [];
        foreach ($order as $key) {
            if (str_contains($key, '*')) {
                $wildcardAttributes = array_filter($attributes, fn ($attrName) => $this->matchesPattern($attrName, [$key]), ARRAY_FILTER_USE_KEY);
                ksort($wildcardAttributes);
                $finalAttributes = array_merge($finalAttributes, $wildcardAttributes);
                $attributes = array_diff_key($attributes, $wildcardAttributes);
            } else {
                if (isset($attributes[$key])) {
                    $finalAttributes[$key] = $attributes[$key];
                    unset($attributes[$key]);
                }
            }
        }

        return $finalAttributes;
    }

    private function matchesPattern(string $attribute, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            if (fnmatch($pattern, $attribute)) {
                return true;
            }
        }

        return false;
    }
}
