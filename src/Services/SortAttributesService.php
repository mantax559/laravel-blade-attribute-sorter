<?php

namespace Mantax559\LaravelBladeAttributeSorter\Services;

class SortAttributesService
{
    private array $attributeOrder;

    public function __construct()
    {
        $this->attributeOrder = array_merge(
            (array) config('laravel-blade-attribute-sorter.default'),
            (array) config('laravel-blade-attribute-sorter.custom'),
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
                $custom
            );
        }

        return $this->attributeOrder;
    }

    public function sortAttributes(string $content): string
    {
        $attributePattern = '/<([a-zA-Z0-9\-:]+)((?:\s+[a-zA-Z\-:.]+(?:\s*=\s*(?:"[^"]*"|\'[^\']*\'|{{[^}]*}}|\S+))?)*)\s*>/m';

        return preg_replace_callback($attributePattern, function ($matches) {
            $tag = $matches[1];
            $attributes = isset($matches[2]) ? trim($matches[2]) : '';

            preg_match_all('/(\S+)=("[^"]*"|\'[^\']*\'|{{[^}]*}}|\S+)|(\S+)/', $attributes, $attributeMatches, PREG_SET_ORDER);

            $sortedAttributes = $this->sortAttributesByOrder($tag, $attributeMatches);

            return "<$tag".($sortedAttributes ? ' '.$sortedAttributes : '').'>';
        }, $content);
    }

    private function sortAttributesByOrder(string $tag, array $attributes): string
    {
        $customOrder = $this->attributeOrder[$tag] ?? null;
        $defaultOrder = $this->attributeOrder['default'];

        $sortedAttributes = [];
        $remainingAttributes = [];

        foreach ($attributes as $attribute) {
            $name = $attribute[1] ?? $attribute[3];
            if ($customOrder && in_array($name, $customOrder)) {
                $sortedAttributes[$name] = trim($attribute[0]);
            } else {
                $remainingAttributes[$name] = trim($attribute[0]);
            }
        }

        $finalAttributes = [];
        if ($customOrder) {
            foreach ($customOrder as $key) {
                if (isset($sortedAttributes[$key])) {
                    $finalAttributes[] = $sortedAttributes[$key];
                }
            }
        }

        foreach ($defaultOrder as $key) {
            if (isset($remainingAttributes[$key])) {
                $finalAttributes[] = $remainingAttributes[$key];
                unset($remainingAttributes[$key]);
            }
        }

        ksort($remainingAttributes);
        foreach ($remainingAttributes as $attr) {
            $finalAttributes[] = $attr;
        }

        return implode(' ', $finalAttributes);
    }
}
