<?php

namespace Oxygen\Core\Console;

use Oxygen\Core\Blueprint\Blueprint;
use Oxygen\Core\Blueprint\BlueprintManager;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

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
    protected $headers = [
        'Name', 'Display Names', 'Controller', 'Primary Toolbar Item', 'Icon'
    ];

    /**
     * Execute the console command.
     *
     * @param \Oxygen\Core\Blueprint\BlueprintManager $manager
     */
    public function handle(BlueprintManager $manager) {
        $blueprints = $manager->all();

        if(empty($blueprints)) {
            $this->error("Your application doesn't have any blueprints.");

            return;
        }

        $generalTable = new Table($this->output);
        $generalTable->setHeaders($this->headers);
        $generalTable->setRows($this->getTable($blueprints));
        $generalTable->render();
    }

    /**
     * Compile the blueprints into a displayable format.
     *
     * @param array $blueprints
     * @return array
     */

    protected function getTable(array $blueprints) {
        $results = [];

        foreach($blueprints as $key => $blueprint) {
            $results[] = $this->getGeneralInformation($key, $blueprint);
        }

        return array_filter($results);
    }

    /**
     * Get the blueprint information.
     *
     * @param string    $key
     * @param Blueprint $blueprint
     * @return array
     */

    protected function getGeneralInformation($key, Blueprint $blueprint) {
        return [
            $blueprint->getName(),
            Formatter::shortArray([$blueprint->getDisplayName(), $blueprint->getPluralDisplayName()]),
            $blueprint->getController(),
            $blueprint->hasPrimaryToolbarItem() ? $blueprint->getPrimaryToolbarItem()->getIdentifier() : 'None',
            $blueprint->getIcon()
        ];
    }

}
