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
                'default' => ['id', 'name', 'class'],
            ],
            'laravel-blade-attribute-sorter.custom' => [
                'input' => ['name', 'id', 'class'],
            ],
        ]);

        $this->sortAttributesService = new SortAttributesService();
    }

    public function test_case_1()
    {
        $this->assertEquals(
            expected: '<div>',
            actual: $this->sortAttributesService->sortAttributes('<div>'),
        );
    }

    public function test_case_2()
    {
        $this->assertEquals(
            expected: '<div id="test">',
            actual: $this->sortAttributesService->sortAttributes('<div id="test">'),
        );
    }

    public function test_case_3()
    {
        $this->assertEquals(
            expected: '<div id="test" name="test">',
            actual: $this->sortAttributesService->sortAttributes('<div name="test" id="test">'),
        );
    }

    public function test_case_4()
    {
        $this->assertEquals(
            expected: '<input name="test" id="test">',
            actual: $this->sortAttributesService->sortAttributes('<input id="test" name="test">'),
        );
    }
}
