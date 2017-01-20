<?php

namespace Milhojas\Messaging\Shared\Inflector;

/**
 * Computes a handler name using a notation compatible with the sy fony container.
 * Convention is:
 * Given: \Vendor\Layer\Context\Folder\ClassName
 * Then:  context.class_name.handler.
 *
 * Important parts are ClassName, Folder and Context.
 * Folder should be Query/Command/Event Can have nested folders:
 * Given: \Vendor\Layer\Context\Folder\Subfolder\ClassName
 * Then:  context.subfolder.class_name.handler
 */
class SymfonyContainerInflector implements Inflector
{
    public function inflect($className)
    {
        $parts = explode('\\', $className);
        $class = preg_replace('/(?<=.)[A-Z]/', '_$0', end($parts));
        $folder = prev($parts);
        while (!in_array($folder,  ['Query', 'Command', 'Event'])) {
            $class = $folder.'.'.$class;
            $folder = prev($parts);
        }
        $context = prev($parts);

        return strtolower(sprintf('%s.%s.handler', $context, $class));
    }
}
