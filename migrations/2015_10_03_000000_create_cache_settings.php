<?php

use Illuminate\Database\Migrations\Migration;
use Oxygen\Core\Preferences\Loader\PreferenceRepositoryInterface;

class CreateCacheSettings extends Migration {

    /**
     * Run the migrations.
     */
    public function up() {
        $preferences = App::make(PreferenceRepositoryInterface::class);

        $item = $preferences->make();
        $item->setKey('cacheSettings');
        $item->setPreferences(['entities' => []]);
        $preferences->persist($item);
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        $preferences = App::make(PreferenceRepositoryInterface::class);

        $preferences->delete($preferences->findByKey('cacheSettings'));
    }
}
