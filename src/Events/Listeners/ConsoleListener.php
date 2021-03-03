<?php

declare(strict_types=1);

namespace Contributte\NewRelic\Events\Listeners;

use Contributte\NewRelic\Agent\Agent;
use Contributte\NewRelic\Helpers;
use Symfony\Component\Console;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ConsoleListener implements EventSubscriberInterface
{

	/**
	 * @var Agent
	 */
	private $agent;

	public function __construct(Agent $agent)
	{
		$this->agent = $agent;
	}

	public function onCommand(Console\Event\ConsoleCommandEvent $event): void
	{
		$this->agent->backgroundJob();
		$this->agent->nameTransaction(Helpers::getConsoleCommand());
		$this->agent->disableAutorum();
	}

	public function onError(Console\Event\ConsoleErrorEvent $event): void
	{
		$this->agent->noticeError($event->getError()->getMessage(), $event->getError());
	}

	/**
	 * @return array<string, string>
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			Console\ConsoleEvents::COMMAND => 'onCommand',
			Console\ConsoleEvents::ERROR => 'onError',
		];
	}

}
