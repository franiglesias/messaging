<?php

namespace Test\Messaging\Shared\Inflector;

use PHPUnit\Framework\TestCase;
use Milhojas\Messaging\Shared\Inflector\SymfonyContainerInflector;

class SymfonyContainerInflectorTest extends TestCase
{
    private $inflector;

    public function setUp()
    {
        $this->inflector = new SymfonyContainerInflector();
    }

    public function test_it_can_manage_vendor_class()
    {
        $class = '\\Vendor\\ClassName';
        $expected = 'vendor.class_name.handler';
        $this->assertEquals(
            $expected,
            $this->inflector->inflect($class)
        );
    }

    public function test_it_can_convert_command_class_name_to_command_handler_standard_name()
    {
        $class = '\\Vendor\\Domain\\Context\\Command\\ClassName';
        $expected = 'context.class_name.handler';
        $this->assertEquals(
            $expected,
            $this->inflector->inflect($class)
        );
    }

    public function test_it_can_convert_subfoldered_command_class_name_to_command_handler_standard_name()
    {
        $class = '\\Vendor\\Domain\\Context\\Command\\Subfolder\\ClassName';
        $expected = 'context.subfolder.class_name.handler';
        $this->assertEquals(
            $expected,
            $this->inflector->inflect($class)
        );
    }

    public function test_it_can_manage_not_standard_name()
    {
        $class = '\\Vendor\\Domain\\Context\\ClassName';
        $expected = 'context.class_name.handler';
        $this->assertEquals(
            $expected,
            $this->inflector->inflect($class)
        );
    }
}
