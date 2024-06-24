<?php

namespace Mantax559\LaravelBladeAttributeSorter\Test\Unit;

use Mantax559\LaravelBladeAttributeSorter\Services\SortAttributesService;
use Orchestra\Testbench\TestCase;

class SortAttributesServiceTest extends TestCase
{
    private SortAttributesService $sortAttributesService;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'laravel-blade-attribute-sorter.default' => ['id', 'name', 'class', 'min', 'max', 'required'],
            'laravel-blade-attribute-sorter.custom' => [
                'input' => ['name', 'id', 'class'],
                'form' => [],
            ],
        ]);

        $this->sortAttributesService = new SortAttributesService();
    }

    public function testSetAttributeOrderWithOnlyDefault(): void
    {
        $defaultOrder = ['data-id', 'data-name', 'data-class'];
        $customOrder = null;

        $expectedOrder = [
            'default' => $defaultOrder,
            'custom' => ['input' => ['name', 'id', 'class']],
        ];

        $result = $this->sortAttributesService->setAttributeOrder($defaultOrder, $customOrder);

        $this->assertEquals($expectedOrder, $result);
    }

    public function testSetAttributeOrderWithOnlyCustom(): void
    {
        $defaultOrder = null;
        $customOrder = ['div' => ['data-id', 'data-name', 'data-class']];

        $expectedOrder = [
            'default' => ['id', 'name', 'class', 'min', 'max', 'required'],
            'custom' => [
                'div' => ['data-id', 'data-name', 'data-class'],
            ],
        ];

        $result = $this->sortAttributesService->setAttributeOrder($defaultOrder, $customOrder);

        $this->assertEquals($expectedOrder, $result);
    }

    public function testSetAttributeOrderWithBothDefaultAndCustom(): void
    {
        $defaultOrder = ['data-id', 'data-name', 'data-class'];
        $customOrder = [
            'div' => ['data-id', 'data-name', 'data-class'],
            'span' => ['class', 'id'],
        ];

        $expectedOrder = [
            'default' => $defaultOrder,
            'custom' => [
                'div' => ['data-id', 'data-name', 'data-class'],
                'span' => ['class', 'id'],
            ],
        ];

        $result = $this->sortAttributesService->setAttributeOrder($defaultOrder, $customOrder);

        $this->assertEquals($expectedOrder, $result);
    }

    public function testSetAttributeOrderWithEmptyBoth(): void
    {
        $defaultOrder = [];
        $customOrder = [];

        $expectedOrder = [
            'default' => ['id', 'name', 'class', 'min', 'max', 'required'],
            'custom' => [
                'input' => ['name', 'id', 'class'],
            ],
        ];

        $result = $this->sortAttributesService->setAttributeOrder($defaultOrder, $customOrder);

        $this->assertEquals($expectedOrder, $result);
    }

    public function testSortAttributesForEmptyDivTag(): void
    {
        $this->assertEquals(
            '<div>',
            $this->sortAttributesService->sortAttributes('<div>')
        );
    }

    public function testSortAttributesForDivTagWithIdAttribute(): void
    {
        $this->assertEquals(
            '<div id="test">',
            $this->sortAttributesService->sortAttributes('<div id="test">')
        );
    }

    public function testSortAttributesForDivTagWithIdAndClassAttributes(): void
    {
        $this->assertEquals(
            '<div id="test" class="test">',
            $this->sortAttributesService->sortAttributes('<div class="test" id="test">')
        );
    }

    public function testSortAttributesForInputTagWithNameAndIdAttributes(): void
    {
        $this->assertEquals(
            '<input name="test" id="test">',
            $this->sortAttributesService->sortAttributes('<input id="test" name="test">')
        );
    }

    public function testSortAttributesForInputTagWithNameIdAndClassAttributes(): void
    {
        $this->assertEquals(
            '<input name="test" id="test" class="test">',
            $this->sortAttributesService->sortAttributes('<input id="test" class="test" name="test">')
        );
    }

    public function testSortAttributesForInputTagWithNameIdClassAndRequiredAttributes(): void
    {
        $this->assertEquals(
            '<input name="test" id="test" class="test" required>',
            $this->sortAttributesService->sortAttributes('<input id="test" required class="test" name="test">')
        );
    }

    public function testSortAttributesForInputTagWithAllCommonAttributes(): void
    {
        $this->assertEquals(
            '<input name="test" id="test" class="test" min="test" max="test" required>',
            $this->sortAttributesService->sortAttributes('<input id="test" max="test" required min="test" class="test" name="test">')
        );
    }

    public function testSortAttributesForInputTagWithAdditionalAttributes(): void
    {
        $this->assertEquals(
            '<input name="test" id=\'test\' class="test" min="test" max="test" required pattern="test" type="text" value="test">',
            $this->sortAttributesService->sortAttributes('<input id=\'test\' type="text" value="test" pattern="test" max="test" required min="test" class="test" name="test">')
        );
    }

    public function testSortAttributesForInputTagWithCustomAndDefaultAttributes(): void
    {
        $this->assertEquals(
            '<input name="custom" id="custom" class="custom" data-custom="value" type="text">',
            $this->sortAttributesService->sortAttributes('<input data-custom="value" type="text" id="custom" class="custom" name="custom">')
        );
    }

    public function testSortAttributesForDivTagWithUnorderedAttributes(): void
    {
        $this->assertEquals(
            '<div id="divId" class="divClass" data-custom="value">',
            $this->sortAttributesService->sortAttributes('<div data-custom="value" class="divClass" id="divId">')
        );
    }

    public function testSortAttributesForSelfClosingDivTagWithUnorderedAttributes(): void
    {
        $this->assertEquals(
            '<div id="divId" class="divClass" data-custom=value />',
            $this->sortAttributesService->sortAttributes('<div data-custom=value class="divClass" id="divId"/>')
        );
    }

    public function testSortAttributesForSelfClosingDivTagWithSpaceBeforeSlash(): void
    {
        $this->assertEquals(
            '<div id="divId" class="divClass" data-custom="value" />',
            $this->sortAttributesService->sortAttributes('<div data-custom="value" class="divClass" id="divId" />')
        );
    }

    public function testSortAttributesForAsyncScriptTag(): void
    {
        $this->assertEquals(
            '<script async src="https://example.com?id={{ $value }}"></script>',
            $this->sortAttributesService->sortAttributes('<script async src="https://example.com?id={{ $value }}"></script>')
        );
    }

    public function testSortAttributesForXFormInputWithCustomOrder(): void
    {
        $defaultOrder = [];
        $customOrder = [
            'x-form::input' => ['enctype', 'wire:click.prevent-custom.select', ':selected', 'action'],
        ];

        $this->sortAttributesService->setAttributeOrder($defaultOrder, $customOrder);

        $this->assertEquals(
            '<x-form::input enctype="multipart/form-data" wire:click.prevent-custom.select="test({{ $index }})" :selected="$testVariable[\'index1\'][\'index2\'] ?? null" action="{{ isset($randomVariable) ? route(\'routes.custom-route.index\', $random_variable->id) : route(\'routes.custom_route.index\') }}" name="array[{{ $index }}][id]" required method="POST" />',
            $this->sortAttributesService->sortAttributes('<x-form::input method="POST" required action="{{ isset($randomVariable) ? route(\'routes.custom-route.index\', $random_variable->id) : route(\'routes.custom_route.index\') }}" enctype="multipart/form-data" name="array[{{ $index }}][id]" :selected="$testVariable[\'index1\'][\'index2\'] ?? null" wire:click.prevent-custom.select="test({{ $index }})"/>'),
        );
    }

    public function testSortAttributesForNestedComponentAttributes(): void
    {
        $defaultOrder = [];
        $customOrder = [
            'x-nested::component' => ['v-bind', '@click', 'data-test'],
        ];

        $this->sortAttributesService->setAttributeOrder($defaultOrder, $customOrder);

        $this->assertEquals(
            '<x-nested::component v-bind="value" @click="handleClick" data-test="example" class="extra-class" />',
            $this->sortAttributesService->sortAttributes('<x-nested::component class="extra-class" @click="handleClick" data-test="example" v-bind="value" />')
        );
    }

    public function testSortAttributesForComplexCustomAttributes(): void
    {
        $defaultOrder = [];
        $customOrder = [
            'x-complex::form' => ['wire:model', 'data-*', ':value', 'aria-*', '@submit'],
        ];

        $this->sortAttributesService->setAttributeOrder($defaultOrder, $customOrder);

        $this->assertEquals(
            '<x-complex::form wire:model="formData" data-custom={{ $random }} :value="formValue" aria-describedby="test" aria-label="test" @submit="handleSubmit" enctype="multipart/form-data" method="POST" />',
            $this->sortAttributesService->sortAttributes('<x-complex::form method="POST" enctype="multipart/form-data" @submit="handleSubmit" data-custom={{ $random }} wire:model="formData" aria-label="test" :value="formValue" aria-describedby="test" />')
        );
    }

    public function testSortAttributesForScriptTagWithNonce(): void
    {
        $this->assertEquals(
            '<script async nonce="random_nonce" src="https://example.com/script.js"></script>',
            $this->sortAttributesService->sortAttributes('<script async src="https://example.com/script.js" nonce="random_nonce"></script>')
        );
    }

    public function testSortAttributesForMixedCaseAttributes(): void
    {
        $this->assertEquals(
            '<div ID="divId" Data-custom="value" simpleClass="divClass">',
            $this->sortAttributesService->sortAttributes('<div Data-custom="value" simpleClass="divClass" ID="divId">')
        );
    }

    public function testSortAttributesForCustomTags(): void
    {
        $customOrder = ['custom-tag' => ['custom-attr1', 'custom-attr2']];

        $this->sortAttributesService->setAttributeOrder([], $customOrder);

        $this->assertEquals(
            '<custom-tag custom-attr1="value1" custom-attr2="value2" :other-attr=$othervalue>',
            $this->sortAttributesService->sortAttributes('<custom-tag :other-attr=$othervalue custom-attr2="value2" custom-attr1="value1">')
        );
    }

    public function testSortAttributesWithHyphenatedAttributes(): void
    {
        $customOrder = ['custom-component' => ['data-custom-id', 'data-custom-name', 'aria-label']];

        $this->sortAttributesService->setAttributeOrder([], $customOrder);

        $this->assertEquals(
            '<custom-component data-custom-id="123" data-custom-name="example" aria-label="label" class="extra-class">',
            $this->sortAttributesService->sortAttributes('<custom-component class="extra-class" aria-label="label" data-custom-name="example" data-custom-id="123">')
        );
    }
}
