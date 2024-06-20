<?php

namespace Mantax559\LaravelBladeAttributeSorter\Services;

class SortAttributesService
{
    private array $attributeOrder;

    public function __construct()
    {
        $this->attributeOrder = array_merge(
            ['default' => config('laravel-blade-attribute-sorter.default')],
            ['custom' => config('laravel-blade-attribute-sorter.custom')],
        );
    }

    public function setAttributeOrder(?array $default, ?array $custom): array
    {
        if (! empty($default)) {
            $this->attributeOrder['default'] = $default;
        }

        if (! empty($custom)) {
            $this->attributeOrder = array_merge(
                ['default' => $this->attributeOrder['default']],
                ['custom' => $custom]
            );
        }

        $this->attributeOrder['default'] = array_filter($this->attributeOrder['default'], fn ($value) => ! empty($value));
        $this->attributeOrder['custom'] = array_filter($this->attributeOrder['custom'], fn ($value) => ! empty($value));

        return $this->attributeOrder;
    }

    public function sortAttributes(string $content): string
    {
        $attributePattern = '/<([a-zA-Z0-9\-:.]+)((?:\s+[a-zA-Z0-9\-:.@]+(?:=(?:"[^"]*"|\'[^\']*\'|{{[^}]*}}|\S+))?)*)\s*(\/?)>/m';

        return preg_replace_callback($attributePattern, function ($matches) {
            $tag = $matches[1];
            $attributes = isset($matches[2]) ? trim($matches[2]) : '';
            $selfClosing = isset($matches[3]) && $matches[3] === '/' ? ' /' : '';

            preg_match_all('/(\S+)=("[^"]*"|\'[^\']*\'|{{[^}]*}}|\S+)|(\S+)/', $attributes, $attributeMatches, PREG_SET_ORDER);

            $sortedAttributes = $this->sortAttributesByOrder($tag, $attributeMatches);

            return "<$tag".($sortedAttributes ? ' '.$sortedAttributes : '').$selfClosing.'>';
        }, $content);
    }

    private function sortAttributesByOrder(string $tag, array $attributes): string
    {
        $customOrder = $this->attributeOrder['custom'][$tag] ?? [];
        $defaultOrder = $this->attributeOrder['default'];

        $sortedAttributes = [];
        $remainingAttributes = [];

        foreach ($attributes as $attribute) {
            $name = $attribute[1] ?? $attribute[3];
            if ($this->matchesPattern($name, $customOrder)) {
                $sortedAttributes[$name] = trim($attribute[0]);
            } else {
                $remainingAttributes[$name] = trim($attribute[0]);
            }
        }

        $finalAttributes = [];
        foreach ($customOrder as $key) {
            if (strpos($key, '*') !== false) {
                $wildcardAttributes = [];
                foreach ($sortedAttributes as $attrName => $attrValue) {
                    if ($this->matchesPattern($attrName, [$key])) {
                        $wildcardAttributes[$attrName] = $attrValue;
                        unset($sortedAttributes[$attrName]);
                    }
                }
                ksort($wildcardAttributes);
                $finalAttributes = array_merge($finalAttributes, $wildcardAttributes);
            } else {
                if (isset($sortedAttributes[$key])) {
                    $finalAttributes[] = $sortedAttributes[$key];
                    unset($sortedAttributes[$key]);
                }
            }
        }

        foreach ($defaultOrder as $key) {
            if (isset($remainingAttributes[$key])) {
                $finalAttributes[] = $remainingAttributes[$key];
                unset($remainingAttributes[$key]);
            }
        }

        asort($remainingAttributes);
        foreach ($remainingAttributes as $attr) {
            $finalAttributes[] = $attr;
        }

        return implode(' ', $finalAttributes);
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
