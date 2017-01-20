<?php

namespace Milhojas\Messaging\Shared\Worker;

use Milhojas\Messaging\Shared\Message;
use Psr\Log\LoggerInterface;

class LoggerWorker extends MessageWorker
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function execute(Message $message)
    {
        $name = $this->getName(get_class($message));
        $this->logger->notice(sprintf('Message %s has been dispatched.', $name));
    }

    private function getName($fqcn)
    {
        $parts = explode('\\', $fqcn);
        $class = preg_replace('/(?<=.)[A-Z]/', '_$0', array_pop($parts));
        $folder = array_pop($parts);
        while ($parts && !in_array($folder,  ['Query', 'Command', 'Event', 'Listener'])) {
            $class = $folder.'.'.$class;
            $folder = array_pop($parts);
        }
        $context = array_pop($parts);

        return strtolower(implode('.', [$context, $class, $folder]));
    }
}
