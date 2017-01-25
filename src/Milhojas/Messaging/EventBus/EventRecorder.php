<?php

namespace Milhojas\Messaging\EventBus;

/**
 * Records plain events to store them temporary and pass them later to an Event Dispatcher.
 *
 * @author Fran Iglesias
 */
class EventRecorder implements \IteratorAggregate
{
    /**
     * Array of stored events.
     *
     * @var array
     */
    private $events = array();

    public function getIterator()
    {
        return new \ArrayIterator($this->events);
    }

    /**
     * Records an Event, appending it to the stored ones.
     *
     * @param Event $event the event to record
     */
    public function recordThat(Event $event)
    {
        array_push($this->events, $event);
    }

    /**
     * Records an array of events.
     *
     * @param array $events an array of events
     */
    public function load(array $events)
    {
        foreach ($events as $event) {
            $this->recordThat($event);
        }
    }
    public function shift()
    {
        return array_shift($this->events);
    }
    /**
     * Retrieves a plain array of events.
     *
     * @return array of events
     */
    public function retrieve()
    {
        return $this->events;
    }

    /**
     * Empties the array of events. Use after current events are dispatched.
     */
    public function flush()
    {
        $this->events = array();
    }

    /**
     * Counts the events stored in the Recorder.
     */
    public function count()
    {
        return count($this->events);
    }

    public function __toString()
    {
        $string = '';
        foreach ($this->events as $event) {
            $string .= $event->getName().chr(10);
        }

        return $string;
    }
}
