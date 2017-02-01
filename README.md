# Milhojas messaging
A Messaging component for apps, with CommandBus, EventBus and QueryBus

Milhojas Messaging is a component that provides internal communication for PHP apps.

It's simple but very flexible and easily extensible.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/82863b81-f238-4b92-8411-5c43ebf64fe8/mini.png)](https://insight.sensiolabs.com/projects/82863b81-f238-4b92-8411-5c43ebf64fe8)

[![Build Status](https://travis-ci.org/franiglesias/messaging.svg?branch=master)](https://travis-ci.org/franiglesias/messaging)


## Getting started

### What is a message bus?

If you want to write a truly decoupled application where layers and components are isolated you need a way for components to communicate between them without the need of having an intimate knowledge. Using a message bus is a good solution for this.

A message bus is a component that delivers messages between parts of an application. In the long term, components are dependent on the message bus, but this could be considered and acceptable trade-off. It is a backbone channel for your application.

A Message, [according to Verraes](http://verraes.net/2015/01/messaging-flavours/), "is a unit of communication between systems". There are three types:

- Imperative messages: instruct the receiver to perform an action or change its state. Usually, we model them as Commands, and receivers are Command Handlers. There is a one to one relationship, this means that for every Command there is a unique Command Handler that handles it. Command Handlers doesn't return state. The change of state should be confirmed using a Query or listening for an Event. If the Command fails it should throw an Exception.
- Interrogative messages: ask the system for information about its state. We model them as Queries, and receivers are Query Handlers. They are similar to Commands except for two things: queries doesn't change state (only report about it) and they must return a response, or throw an exception if they can not get the data.
- Informational messages: a part of a system communicates something about itself. We model them as Events. Events are raised by a part of the system that experiments a change (usually consequence of a command) and they could be interesting for many other components in the application, that we call Listeners. One or more listeners (o none at all) can wait for an event to be dispatched and act on it.

So, a message bus is a way to move all these messages in the heart of your application.

### Several message buses

In our implementation there are three message buses, according to the three types of messages:

- A command bus, to send commands to the right handlers.
- A query bus, to send queries to the handlers that can answer them.
- An event bus, to dispatch events to listeners waiting for them.

#### The command bus

The command bus delivers commands to their handlers.

A Command is an Immutable Data Transfer Object that carries the data needed to perform the action to the Handler. Command implements `Command` Interface that extends from Message interface.

The Command Handler is an object that uses this information to perform a task. It implements the `CommandHandler` interface, so it must implement a `handle(Command $command)` method. Command Handlers should not return anything, but they could rise Events, or the system itself could raise the Events. If something goes wrong it should throw an Exception.

#### The query bus

The query bus delivers queries to the right handlers, and returns a response.

A Query is an Immutable Data Transfer Object that carries the data needed to perform the query and get the information needed. Query implement the `Query` Interface that extends from Message interface.

A Query Handler is an object that uses this information to perform its task and get a response from the system. It implements the `QueryHandler` interface, so it must implement a `answer(Query $query)` method that returns a response. If a response is not available it should throw an Exception, but it could return an empty response if there is no information to retrieve. For example: getting a non existent user should throw an exception, but trying to find several posts published on a date could return an empty response if there are none for that concrete date.

#### The event bus

The event bus dispatches events to listeners interested in them, if any.

An Event is an Immutable Data Transfer Object that carries the data related to the change or event that had just happened in a part of a system. Event implements the `Event` interface that extends from Message interface and forces that Events should have a method `getName()` that returns an identification name for the event.

A Listener is an object that uses the information of the Event to perform some task. It implements the `Listener` interface, so it must implement a `handle(Event $event)` method. If there are no listeners to handle an event, the bus must fail silently. Listeners could fail, but they should no interrupt the Event flow.

## How they work

Message Bus classes are very simple ones. They delegate all the hard work to Workers or Pipelines. A Worker is an object that do something with the messages received. A Pipeline is an array of workers that receives the same message in sequence. In fact, both Workers and Pipelines implement the `Worker` interface, so they are interchangeable.

Workers are called Middleware in other MessageBus implementations. I dislike the Middleware naming convention. Worker means "this thing do a job", and Middleware means "this thing stays in the middle".

So, a message bus needs at least a Worker to, ahem!, work. Every kind of bus in fact need at least an specific worker that performs its main task (send messages to their correspondent handlers).

- CommandBus needs ExecuteWorker.
- QueryBus needs QueryWorker.
- EventBus needs DispatcherWorker.

This could sound a little strange but it give us some advantages:

- Basic Worker implementations can be replaced, so you could start with the provided implementation and replace in a future with another one of your own, provided that it implements the Worker interface.
- You can pipeline several workers, like the LoggerWorker o DispatchEventsWorker (provided in this package) to perform several task before of after messages are processed. This bring a lot of flexibility. You can write your own workers or another implementation for the current ones.
- Some workers can be shared between buses. For example, the same instance of LoggerWorker can be consumed by any of the three buses.

## Meet the Buses

### CommandBus

CommandBus is the class that communicates Commands with CommandHandlers. It needs at least an ExecuteWorker to be constructor injected, but ExecuteWorker needs some collaborators too. So, we will review them first.

#### ExecuteWorker

ExecuteWorker receives a Command, guess the CommandHandler from the Command fully qualified class name (FQCN) using an `Inflector` and loads it with the help of a `Loader`, that usually is an Adapter for a Dependency Injection Container.

So, to instance an ExcuteWorker we need first an Inflector and a Loader.

##### Inflector

Milhojas Messaging comes with both a simple Inflector class and a Loader class.

The Inflector class uses a convention to convert the FQCN of a Command to a key that identifies a CommandHandler in a Dependency Injection Container (DIC), so the Loader can get it. The implementation provided is ContainerInflector.

I usually group related Commands in Contexts (think on the Bounded Contexts of DDD), and the folder and files organization reflects that. For example, given a Command with FQN of:

`\Milhojas\Application\Context\Command\RegisterUser`

the Inflector should resolve that the Handler key is:

`context.register_user.handler`

I pack the Command and the Command Handler in the same folder, so the corresponding handler for the example will be:

`\Milhojas\Application\Context\Command\RegisterUserHandler`

and the name in the DIC will be:

`context.register_user.handler`

Got it?

The code to instantiate the Inflector:

```
    use Milhojas\Messaging\Shared\Inflector\ContainerInflector;
    $inflector = new ContainerInflector();
```

So if you want to use the ContainerLoader you should create a DIC key for your handlers.

##### The Loader

The Loader needs to be injected with an Adapter of a DI container. Any Interop/ContainerInterface implementation should be OK. Other containers should need and adapter. Milhojas Messaging bring a SymfonyContainer adapter that uses the ContainerAware trait.

The code:

```
    use Milhojas\Messaging\Shared\Loader\ContainerLoader;
    use Milhojas\Messaging\Shared\Loader\Container\SymfonyContainer;

    $loader = new ContainerLoader(new SymfonyContainer);
```
##### Wait a moment. Why do you need a container?

Message Handlers are little objects oriented to a task, but usually they need several collaborators and parameters to perform it. The best way to instantiate them is through a dependency injection container, the buses need the handlers instantiated to work.

In fact, you should use a DIC both to build the handlers and the buses themselves.

#### Back to the Worker

Now we have the collaborators for ExecuteWorker, it's time to instantiate it:

```
    use Milhojas\Messaging\CommandBus\Worker\ExecuteWorker;

    $executeWorker = new ExecuteWorker($inflector, $loader);
```

### Back to the bus

For this time we are going to use only the basic worker, and leave the pipelines to another day. To build the CommandBus, we need the following code:

```
    use Milhojas\Messaging\CommandBus\Worker\ExecuteWorker;

    $commandBus = new CommandBus($executeWorker);
```

The complete code should look like this:

```
    use Milhojas\Messaging\Shared\Inflector\ContainerInflector;
    use Milhojas\Messaging\Shared\Loader\ContainerLoader;
    use Milhojas\Messaging\Shared\Loader\Container\SymfonyContainer;
    use Milhojas\Messaging\CommandBus\Worker\ExecuteWorker;
    use Milhojas\Messaging\CommandBus\Worker\ExecuteWorker;

    $inflector = new ContainerInflector();
    $loader = new ContainerLoader(new SymfonyContainer);
    $executeWorker = new ExecuteWorker($inflector, $loader);
    $commandBus = new CommandBus($executeWorker);
```


More Documentation soon...
