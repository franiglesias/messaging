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
class ContainerInflector implements Inflector
{
    public function inflect($fqcn)
    {
        $split = $this->splitLastDot($this->normalizeName($fqcn));
        $parts = preg_split('/(query|command|event)/', strtolower($split['left']));
        if (!isset($parts[1])) {
            $parts[1] = '';
        }

        return sprintf('%s.%s.handler', $this->computeContext($parts[0]), $this->computeClassName($split, $parts[1]));
    }

    /**
     * @param string $split
     * @param string $after
     *
     * @return string
     */
    public function computeClassName($split, $after)
    {
        $className = $this->toUnderscore($split['right']);
        if ($after) {
            $className = trim($after, '.').'.'.$className;
        }

        return $className;
    }

    /**
     * @param string $before
     *
     * @return string
     */
    public function computeContext($before)
    {
        $split = $this->splitLastDot($before);
        if (!$split['right']) {
            $split = $this->splitLastDot($split['left']);
            if (!$split['right']) {
                $split['right'] = $split['left'];
            }
        }

        return $split['right'];
    }

    /**
     * @param string $fqcn
     *
     * @return string
     */
    public function normalizeName($fqcn)
    {
        return trim(str_replace('\\', '.', $fqcn), '.');
    }

    private function splitLastDot($string)
    {
        if (strpos($string, '.') === false) {
            return ['left' => $string, 'right' => ''];
        }
        preg_match('/^(.*)\.(.*)$/', $string, $matches);

        $split = ['left' => trim($matches[1], '.'), 'right' => $matches[2]];
        if (!$split['left']) {
            $split['left'] = trim($string);
        }

        return $split;
    }

    private function toUnderscore($string)
    {
        return strtolower(preg_replace('/(?<=.)[A-Z]/', '_$0', $string));
    }
}
