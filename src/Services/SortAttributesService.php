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
            $attributes = trim($matches[2]);
            $selfClosing = $matches[3] === '/' ? ' /' : '';
            $attributeMatches = $this->getAttributeMatches($attributes);
            $sortedAttributes = $this->sortAttributesByOrder($tag, $attributeMatches);

            return "<$tag".($sortedAttributes ? ' '.$sortedAttributes : '').$selfClosing.'>';
        }, $content);
    }

    private function getAttributeMatches(string $attributes): array
    {
        $attributes = preg_replace('/\s+/', ' ', $attributes);
        $attributes = trim($attributes);

        $valueOpenChar = '';
        $valueCloseChar = '';
        $getValueOpenChar = false;
        $isName = true;
        $name = '';
        $value = '';
        $skip = false;
        $attributeMatches = [];
        $closingTagIsTwoChars = false;
        foreach (str_split($attributes) as $letter) {
            if ($skip) {
                $skip = false;
            } elseif ($getValueOpenChar) {
                $valueOpenChar = $letter;
                $valueCloseChar = $valueOpenChar === '{' ? '}' : $valueOpenChar;
                $closingTagIsTwoChars = $valueOpenChar === '{';
                $getValueOpenChar = false;
            } elseif ($valueCloseChar === $letter) {
                if($closingTagIsTwoChars) {
                    $value .= $letter;
                    $closingTagIsTwoChars = false;
                    continue;
                }
                $attributeMatches[] = [
                    'name' => $name,
                    'value' => $value,
                    'attribute' => "$name=$valueOpenChar$value$valueCloseChar",
                ];
                $valueOpenChar = '';
                $valueCloseChar = '';
                $getValueOpenChar = false;
                $isName = true;
                $name = '';
                $value = '';
                $skip = true;
            } elseif ($letter === '=' && $isName) {
                $isName = false;
                $getValueOpenChar = true;
            } elseif ($letter === ' ' && $isName) {
                $attributeMatches[] = [
                    'name' => $name,
                    'value' => $name,
                    'attribute' => $name,
                ];
                $valueOpenChar = '';
                $valueCloseChar = '';
                $getValueOpenChar = false;
                $isName = true;
                $name = '';
                $value = '';
            } elseif ($isName) {
                $name .= $letter;
            } else {
                $value .= $letter;
            }
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

        $finalAttributes = [];
        foreach ($customOrder as $key) {
            if (str_contains($key, '*')) {
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
