<?php

namespace Oxygen\Core\Console;

use App;

use Oxygen\Core\Console\Formatter;
use Oxygen\Core\Console\Command;
use Oxygen\Core\Blueprint\Blueprint;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\Table;

class BlueprintListCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */

	protected $name = 'blueprint:list';

	/**
	 * The console command description.
	 *
	 * @var string
	 */

	protected $description = 'Lists all the registered blueprints for the application.';

	/**
	 * The table headers for the command.
	 *
	 * @var array
	 */

	protected $headers = array(
		'Name', 'Display Names', 'Controller', 'Primary Toolbar Item', 'Title Field', 'Icon'
	);

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */

	public function __construct() {
		parent::__construct();

		$this->blueprints = App::make('oxygen.blueprintManager')->all();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */

	public function fire() {
		if (count($this->blueprints) == 0) {
			return $this->error("Your application doesn't have any blueprints.");
		}


		$generalTable = new Table($this->output);
		$generalTable->setHeaders($this->headers);
		$generalTable->setRows($this->getTable());
		$generalTable->render();
	}

	/**
	 * Compile the blueprints into a displayable format.
	 *
	 * @return array
	 */

	protected function getTable() {
		$results = array();

		foreach ($this->blueprints as $key => $blueprint)
		{
			$results[] = $this->getGeneralInformation($key, $blueprint);
		}

		return array_filter($results);
	}

	/**
	 * Get the blueprint information.
	 *
	 * @param string $key
	 * @param Blueprint $blueprint
	 * @return array
	 */

	protected function getGeneralInformation($key, Blueprint $blueprint) {
		return [
			$blueprint->getName(),
			Formatter::shortArray([$blueprint->getDisplayName(), $blueprint->getDisplayName(Blueprint::PLURAL)]),
			$blueprint->getController(),
			$blueprint->hasPrimaryToolbarItem() ? $blueprint->getPrimaryToolbarItem()->getIdentifier() : 'None',
			$blueprint->hasTitleField() ? $blueprint->getTitleField() : 'None',
			$blueprint->getIcon()
		];
	}

}
