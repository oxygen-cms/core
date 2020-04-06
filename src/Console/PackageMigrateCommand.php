<?php


namespace Oxygen\Core\Console;

use Illuminate\Console\ConfirmableTrait;
use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Database\Migrations\Migrator;
use Oxygen\Core\Database\AutomaticMigrator;
use Symfony\Component\Console\Input\InputOption;

class PackageMigrateCommand extends BaseCommand {

    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'migrate:packages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the database migrations for multiple packages';

    /**
     * The migrator instance.
     *
     * @var \Illuminate\Database\Migrations\Migrator
     */
    protected $migrator;

    /**
     * @var \Oxygen\Core\Database\AutomaticMigrator
     */
    protected $paths;

    /**
     * Create a new migration command instance.
     *
     * @param  \Illuminate\Database\Migrations\Migrator $migrator
     * @param \Oxygen\Core\Database\AutomaticMigrator   $paths
     */
    public function __construct(Migrator $migrator, AutomaticMigrator $paths) {
        parent::__construct();

        $this->migrator = $migrator;
        $this->paths = $paths;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        if(!$this->confirmToProceed()) {
            return;
        }

        $this->prepareDatabase();

        // The pretend option can be used for "simulating" the migration and grabbing
        // the SQL queries that would fire if the migration were to be run against
        // a database for real, which is helpful for double checking migrations.
        $pretend = $this->input->getOption('pretend');

        // migrate each path
        foreach($this->paths->getPaths() as $package => $path) {
            $this->info('Running migrations for ' . $package);

            $this->migrator->setOutput($this->output);
            $this->migrator->run($path, $pretend);
        }
    }

    /**
     * Prepare the migration database for running.
     *
     * @return void
     */
    protected function prepareDatabase() {
        $this->migrator->setConnection($this->input->getOption('database'));

        if(!$this->migrator->repositoryExists()) {
            $options = ['--database' => $this->input->getOption('database')];

            $this->call('migrate:install', $options);
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() {
        return [
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],

            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],

            ['pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.']
        ];
    }

}
