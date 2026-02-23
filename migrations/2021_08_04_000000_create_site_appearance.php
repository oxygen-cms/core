<?php

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Database\Migrations\Migration;
use Oxygen\Data\Exception\NoResultException;
use Oxygen\Core\Preferences\Loader\Database\DoctrinePreferenceRepository;
use Oxygen\Core\Preferences\Loader\Database\PreferenceItem;

class CreateSiteAppearance extends Migration {

    /**
     * Run the migrations.
     */
    public function up() {
        $preferences = app(DoctrinePreferenceRepository::class);

        $item = new PreferenceItem();
        $item->setKey('appearance.site');
        $item->setPreferences([]);
        $preferences->persist($item);
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        try {
            $preferences = app(DoctrinePreferenceRepository::class);
            $elem = $preferences->findByKey('appearance.site');
            app(EntityManagerInterface::class)->delete($elem);
            app(EntityManagerInterface::class)->flush();
        } catch(NoResultException $e) {
            // already gone
        }
    }
}
