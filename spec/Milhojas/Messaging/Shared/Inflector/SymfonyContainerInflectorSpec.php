<?php

namespace spec\Milhojas\Messaging\Shared\Inflector;

use Milhojas\Messaging\Shared\Inflector\Inflector;
use Milhojas\Messaging\Shared\Inflector\SymfonyContainerInflector;
use PhpSpec\ObjectBehavior;

class SymfonyContainerInflectorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(SymfonyContainerInflector::class);
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
}
