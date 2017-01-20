<?php

namespace Milhojas\Messaging\EventBus\Reporter;

use Milhojas\Messaging\EventBus\Event;
use Milhojas\Messaging\EventBus\Listener;
use Milhojas\Infrastructure\Mail\MailMessage;
use Milhojas\Infrastructure\Mail\Mailer;
/**
* An EmailReporter is a kind of Listener that can send emails
* 
* Sends an e email message to notify that some event has happened
*/
abstract class EmailReporter implements Listener
{
	private $mailer;
	private $sender;
	private $recipient;
	private $template;
	
	/**
	 * If you need to overwrite the constructor don't forget to call the parent
	 *
	 * @param Mailer $mailer 
	 * @param string $sender 
	 * @param string $recipient 
	 * @param string $template 
	 * @author Francisco Iglesias Gómez
	 */
	public function __construct(Mailer $mailer, $sender, $recipient, $template)
	{
		$this->mailer = $mailer;
		$this->sender = $sender;
		$this->recipient = $recipient;
		$this->template = $template;
	}
	
	/**
	 * Handles the Event, it's the same for all, so you don't need to overwrite
	 *
	 * @param Event $event 
	 * @return void
	 * @author Francisco Iglesias Gómez
	 */
	public function handle(Event $event)
	{
		$this->sendEmail($event);
	}
	
	/**
	 * Returns array of template parameters
	 *
	 * @param Event $event 
	 * @return keyed array where keys are the name of template variables and value are the value
	 * @author Francisco Iglesias Gómez
	 */
	abstract protected function prepareTemplateParameters(Event $event);
	
	private function sendEmail($event)
	{
		$message = new MailMessage();
		$message
			->setTo($this->recipient)
			->setSender($this->sender)
			->setTemplate($this->template, $this->prepareTemplateParameters($event));
		return $this->mailer->send($message);
	}
	
}
?>
