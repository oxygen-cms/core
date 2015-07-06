<?php

namespace Oxygen\Core\Console;

use Oxygen\Core\Blueprint\BlueprintManager;
use Oxygen\Core\Blueprint\Blueprint;
use Oxygen\Core\Action\Action;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\Table;

class BlueprintDetailCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */

	protected $name = 'blueprint:detail';

	/**
	 * The console command description.
	 *
	 * @var string
	 */

	protected $description = 'Shows detailed information about a given blueprint.';

	/**
	 * The table headers for the command.
	 *
	 * @var array
	 */
	protected $generalHeaders = [
		'Names', 'Display Names', 'Route Name', 'Route Pattern', 'Controller', 'Primary Toolbar Item', 'Icon'
	];

	/**
	 * The table headers for the command.
	 *
	 * @var array
	 */
	protected $actionHeaders = [
		'Pattern', 'Name', 'Method', 'Group', 'Middleware', 'Uses', 'Register At End', 'Use Smooth State'
	];

    /**
     * Execute the console command.
     *
     * @param \Oxygen\Core\Blueprint\BlueprintManager $blueprints
     * @return mixed
     * @throws \Exception
     */
	public function handle(BlueprintManager $blueprints) {
		$blueprint = $blueprints->get($this->argument('name'));

		$this->heading('General Information');

		$this->table($this->generalHeaders, [$this->getGeneralInformation($blueprint)]);

		$this->heading('Toolbars');

		foreach($blueprint->getToolbarOrders() as $name => $contents) {
			$this->output->writeln($name . ': ' . Formatter::keyedArray($contents));
		}

		$this->heading('Actions');

		$actions = [];
		foreach($blueprint->getActions() as $action) {
			$actions[] = $this->getActionInformation($action);
		}
		$this->table($this->actionHeaders, $actions);
	}

	/**
	 * Get the blueprint information.
	 *
	 * @param Blueprint $blueprint
	 * @return array
	 */

	protected function getGeneralInformation(Blueprint $blueprint) {
		return [
            Formatter::shortArray([$blueprint->getName(), $blueprint->getPluralName()]),
			Formatter::shortArray([$blueprint->getDisplayName(), $blueprint->getPluralDisplayName()]),
			$blueprint->getRouteName(),
			$blueprint->getRoutePattern(),
			$blueprint->getController(),
			$blueprint->hasPrimaryToolbarItem() ? $blueprint->getPrimaryToolbarItem()->getIdentifier() : 'None',
			$blueprint->getIcon()
		];
	}

	/**
	 * Get information about an Action.
	 *
	 * @param Action $action
	 * @return array
	 */

	protected function getActionInformation(Action $action) {
		return [
			$action->getPattern(),
			$action->getName(),
			$action->getMethod(),
			$action->group->getName() . ', ' . $action->group->getPattern(),
			Formatter::shortArray($action->getMiddleware()),
			$action->getUses(),
			Formatter::boolean($action->register),
			Formatter::boolean($action->useSmoothState)
		];
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments() {
		return [
			['name', InputArgument::REQUIRED, 'Name of the Blueprint.'],
		];
	}

}
