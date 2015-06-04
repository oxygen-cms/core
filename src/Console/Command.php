<?php

namespace Oxygen\Core\Console;

use Illuminate\Console\Command as BaseCommand;

abstract class Command extends BaseCommand {

    /**
     * Prints a heading.
     *
     * @param string $text
     */
    public function heading($text) {
        $this->info('');
        $this->info('===================');
        $this->info($text);
        $this->info('===================');
        $this->info('');
    }

}
