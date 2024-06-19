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
            'laravel-blade-attribute-sorter.default' => [
                'default' => ['id', 'name', 'class', 'min', 'max', 'required'],
            ],
            'laravel-blade-attribute-sorter.custom' => [
                'input' => ['name', 'id', 'class'],
                'form' => [],
            ],
        ]);

        $this->sortAttributesService = new SortAttributesService();
    }

    public function testSetAttributeOrderWithOnlyDefault()
    {
        $defaultOrder = ['data-id', 'data-name', 'data-class'];
        $customOrder = null;

        $expectedOrder = [
            'default' => $defaultOrder,
            'input' => ['name', 'id', 'class'],
        ];

        $result = $this->sortAttributesService->setAttributeOrder($defaultOrder, $customOrder);

        $this->assertEquals($expectedOrder, $result);
    }

    public function testSetAttributeOrderWithOnlyCustom()
    {
        $defaultOrder = null;
        $customOrder = ['div' => ['data-id', 'data-name', 'data-class']];

        $expectedOrder = [
            'default' => ['id', 'name', 'class', 'min', 'max', 'required'],
            'div' => ['data-id', 'data-name', 'data-class'],
        ];

        $result = $this->sortAttributesService->setAttributeOrder($defaultOrder, $customOrder);

        $this->assertEquals($expectedOrder, $result);
    }

    public function testSetAttributeOrderWithBothDefaultAndCustom()
    {
        $defaultOrder = ['data-id', 'data-name', 'data-class'];
        $customOrder = ['div' => ['data-id', 'data-name', 'data-class'], 'span' => ['class', 'id']];

        $expectedOrder = [
            'default' => $defaultOrder,
            'div' => ['data-id', 'data-name', 'data-class'],
            'span' => ['class', 'id'],
        ];

        $result = $this->sortAttributesService->setAttributeOrder($defaultOrder, $customOrder);

        $this->assertEquals($expectedOrder, $result);
    }

    public function testSetAttributeOrderWithEmptyBoth()
    {
        $defaultOrder = [];
        $customOrder = [];

        $expectedOrder = [
            'default' => ['id', 'name', 'class', 'min', 'max', 'required'],
            'input' => ['name', 'id', 'class'],
        ];

        $result = $this->sortAttributesService->setAttributeOrder($defaultOrder, $customOrder);

        $this->assertEquals($expectedOrder, $result);
    }

    public function testEmptyDivTag()
    {
        $this->assertEquals(
            '<div>',
            $this->sortAttributesService->sortAttributes('<div>')
        );
    }

    public function testDivTagWithIdAttribute()
    {
        $this->assertEquals(
            '<div id="test">',
            $this->sortAttributesService->sortAttributes('<div id="test">')
        );
    }

    public function testDivTagWithIdAndClassAttributes()
    {
        $this->assertEquals(
            '<div id="test" class="test">',
            $this->sortAttributesService->sortAttributes('<div class="test" id="test">')
        );
    }

    public function testInputTagWithNameAndIdAttributes()
    {
        $this->assertEquals(
            '<input name="test" id="test">',
            $this->sortAttributesService->sortAttributes('<input id="test" name="test">')
        );
    }

    public function testInputTagWithNameIdAndClassAttributes()
    {
        $this->assertEquals(
            '<input name="test" id="test" class="test">',
            $this->sortAttributesService->sortAttributes('<input id="test" class="test" name="test">')
        );
    }

    public function testInputTagWithNameIdClassAndRequiredAttributes()
    {
        $this->assertEquals(
            '<input name="test" id="test" class="test" required>',
            $this->sortAttributesService->sortAttributes('<input id="test" required class="test" name="test">')
        );
    }

    public function testInputTagWithAllCommonAttributes()
    {
        $this->assertEquals(
            '<input name="test" id="test" class="test" min="test" max=test required>',
            $this->sortAttributesService->sortAttributes('<input id="test" max=test required min="test" class="test" name="test">')
        );
    }

    public function testInputTagWithAdditionalAttributes()
    {
        $this->assertEquals(
            '<input name=test id=\'test\' class=test min="test" max="test" required pattern="test" type="text" value="test">',
            $this->sortAttributesService->sortAttributes('<input id=\'test\'     type="text" value="test"    pattern="test" max="test"   required min="test"   class=test  name=test>')
        );
    }

    public function testInputTagWithCustomAndDefaultAttributes()
    {
        $this->assertEquals(
            '<input name="custom" id="custom" class="custom" data-custom="value" type="text">',
            $this->sortAttributesService->sortAttributes('<input data-custom="value" type="text" id="custom" class="custom" name="custom">')
        );
    }

    public function testDivTagWithUnorderedAttributes()
    {
        $this->assertEquals(
            '<div id="divId" class="divClass" data-custom="value">',
            $this->sortAttributesService->sortAttributes('<div data-custom="value" class="divClass" id="divId">')
        );
    }

    public function test_1()
    {
        $this->assertEquals(
            '<div id="divId" class="divClass" data-custom="value" />',
            $this->sortAttributesService->sortAttributes('<div data-custom="value" class="divClass" id="divId"/>')
        );
    }

    public function test_2()
    {
        $this->assertEquals(
            '<div id="divId" class="divClass" data-custom="value" />',
            $this->sortAttributesService->sortAttributes('<div data-custom="value" class="divClass" id="divId" />')
        );
    }

    public function test_4()
    {
        $defaultOrder = [];
        $customOrder = [];

        $this->sortAttributesService->setAttributeOrder($defaultOrder, $customOrder);

        $this->assertEquals(
            '<x-form::input name="product_attribute_values[{{ $index }}][attribute_value_id]" :selected="$productAttributeValue[\'pivot\'][\'attribute_value_id\'] ?? null" action="{{ isset($attributeGroup) ? route(\'catalog.attribute-groups.update\', $attributeGroup->id) : route(\'catalog.attribute-groups.store\') }}" enctype="multipart/form-data" method="POST" wire:click.prevent="removeProductAttributeValue({{ $index }})" />',
            $this->sortAttributesService->sortAttributes('<x-form::input method="POST" action="{{ isset($attributeGroup) ? route(\'catalog.attribute-groups.update\', $attributeGroup->id) : route(\'catalog.attribute-groups.store\') }}" enctype="multipart/form-data" name="product_attribute_values[{{ $index }}][attribute_value_id]" :selected="$productAttributeValue[\'pivot\'][\'attribute_value_id\'] ?? null" wire:click.prevent="removeProductAttributeValue({{ $index }})"/>'),
        );
    }
}
