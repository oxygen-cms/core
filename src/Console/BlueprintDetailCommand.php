<?php

namespace Oxygen\Core\Console;

use App;

use Oxygen\Core\Console\Command;
use Oxygen\Core\Console\Formatter;

use Oxygen\Core\Blueprint\Blueprint;
use Oxygen\Core\Action\Action;
use Oxygen\Core\Form\Field;

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
	protected $generalHeaders = array(
		'Name', 'Display Names', 'Route Name', 'Route Pattern', 'Controller', 'Primary Toolbar Item', 'Title Field', 'Icon'
	);

	/**
	 * The table headers for the command.
	 *
	 * @var array
	 */
	protected $actionHeaders = array(
		'Pattern', 'Name', 'Method', 'Group', 'Before Filters', 'After Filters', 'Uses', 'Register At End', 'Use Smooth State'
	);

	/**
	 * The table headers for the command.
	 *
	 * @var array
	 */
	protected $fieldHeaders = array(
		'Name', 'Label', 'Type', 'Editable', 'Fillable', 'Validation Rules', 'Attributes', 'Options'
	);

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */

	public function __construct() {
		parent::__construct();

		$this->blueprintManager = App::make('oxygen.blueprintManager');
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire() {
		$blueprint = $this->blueprintManager->get($this->argument('name'));

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

		$this->heading('Fields');

		$fields = [];
		foreach($blueprint->getFields() as $field) {
			$fields[] = $this->getFieldInformation($field);
		}
		$this->table($this->fieldHeaders, $fields);
	}

	/**
	 * Get the blueprint information.
	 *
	 * @param Blueprint $blueprint
	 * @return array
	 */

	protected function getGeneralInformation(Blueprint $blueprint) {
		return [
			$blueprint->getName(),
			Formatter::shortArray([$blueprint->getDisplayName(), $blueprint->getDisplayName(Blueprint::PLURAL)]),
			$blueprint->getRouteName(),
			$blueprint->getRoutePattern(),
			$blueprint->getController(),
			$blueprint->hasPrimaryToolbarItem() ? $blueprint->getPrimaryToolbarItem()->getIdentifier() : 'None',
			$blueprint->hasTitleField() ? $blueprint->getTitleField() : 'None',
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
			$action->group->name . ', ' . $action->group->pattern,
			Formatter::shortArray($action->getBeforeFilters()),
			Formatter::shortArray($action->getAfterFilters()),
			$action->getUses(),
			Formatter::boolean($action->registerAtEnd),
			Formatter::boolean($action->useSmoothState)
		];
	}

	/**
	 * Get information about a Field.
	 *
	 * @param Field $field
	 * @return array
	 */

	protected function getFieldInformation(Field $field) {
		return [
			$field->name,
			$field->label,
			$field->type,
			Formatter::boolean($field->editable),
			Formatter::boolean($field->fillable),
			Formatter::shortArray($field->validationRules),
			Formatter::keyedArray($field->attributes),
			Formatter::keyedArray($field->options)
		];
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments() {
		return array(
			array('name', InputArgument::REQUIRED, 'Name of the Blueprint.'),
		);
	}

}
