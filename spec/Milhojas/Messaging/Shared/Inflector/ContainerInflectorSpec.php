<?php

namespace spec\Milhojas\Messaging\Shared\Inflector;

use Milhojas\Messaging\Shared\Inflector\Inflector;
use Milhojas\Messaging\Shared\Inflector\ContainerInflector;
use PhpSpec\ObjectBehavior;

class ContainerInflectorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ContainerInflector::class);
        $this->shouldImplement(Inflector::class);
    }

    public function it_can_inflect_a_namespaced_class_name()
    {
        $this->inflect('\\Milhojas\\Application\\Context\\Query\\QueryClass')->shouldBe('context.query_class.handler');
    }

    public function it_can_inflect_a_class_name_in_subfolder()
    {
        $this->inflect('\\Milhojas\\Application\\Context\\Query\\Subfolder\\QueryClass')->shouldBe('context.subfolder.query_class.handler');
    }

    public function it_can_inflect_a_class_name_in_nested_folders()
    {
        $this->inflect('\\Milhojas\\Application\\Context\\Query\\Subfolder\\Subsubfolder\\QueryClass')->shouldBe('context.subfolder.subsubfolder.query_class.handler');
    }

    public function it_can_inflect_a_concrete_command()
    {
        $this->inflect('\\Milhojas\\Application\\Management\\Command\\StartPayroll')->shouldBe('management.start_payroll.handler');
        $this->inflect('Milhojas\\Application\\Management\\Command\\StartPayroll')->shouldBe('management.start_payroll.handler');
    }
}
