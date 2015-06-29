<?php

namespace Oxygen\Core\Console;

use Illuminate\Contracts\Container\Container;
use Oxygen\Core\Form\FieldMetadata;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\Table;

class FieldSetDetailCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */

    protected $name = 'blueprint:fields';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Lists form fields';

    /**
     * The table headers for the command.
     *
     * @var array
     */
    protected $fieldHeaders = [
        'Name', 'Label', 'Type', 'Editable', 'Attributes', 'Options'
    ];

    /**
     * Execute the console command.
     *
     * @param \Illuminate\Contracts\Container\Container $container
     * @return mixed
     */
    public function handle(Container $container) {
        $fieldSet = $container->make($this->argument('field'));

        $this->heading('Field Set');

        $fields = [];
        foreach($fieldSet->getFields() as $field) {
            $fields[] = $this->getFieldInformation($field);
        }
        $this->table($this->fieldHeaders, $fields);
    }

    /**
     * Get information about a Field.
     *
     * @param FieldMetadata $field
     * @return array
     */

    protected function getFieldInformation(FieldMetadata $field) {
        return [
            $field->name,
            $field->label,
            $field->type,
            Formatter::boolean($field->editable),
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
        return [
            ['field', InputArgument::REQUIRED, 'Class Name of the FieldSet'],
        ];
    }

}
