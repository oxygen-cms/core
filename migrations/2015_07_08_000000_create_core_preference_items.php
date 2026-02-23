<?php

use Illuminate\Database\Migrations\Migration;
use Oxygen\Core\Preferences\Loader\PreferenceRepositoryInterface;

class CreateCorePreferenceItems extends Migration {

    /**
     * Run the migrations.
     */
    public function up() {
        $preferences = App::make(PreferenceRepositoryInterface::class);

        $item = $preferences->make();
        $item->setKey('appearance.themes');
        $item->setPreferences([]);
        $preferences->persist($item, false);

        $item = $preferences->make();
        $item->setKey('system.admin');
        $item->setPreferences([
            'adminUriPrefix' => 'oxygen'
        ]);
        $preferences->persist($item, false);

        $preferences->flush();
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        $preferences = App::make(PreferenceRepositoryInterface::class);

        $preferences->delete($preferences->findByKey('appearance.themes'), false);
        $preferences->delete($preferences->findByKey('system.admin'), false);

        $preferences->flush();
    }
}
