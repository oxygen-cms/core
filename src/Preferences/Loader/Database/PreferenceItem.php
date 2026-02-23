<?php

namespace Oxygen\Core\Preferences\Loader\Database;

use Oxygen\Data\Behaviour\PrimaryKey;
use Oxygen\Data\Validation\Validatable;
use Oxygen\Core\Preferences\PreferencesStorageInterface;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="preferences")
 * @ORM\HasLifecycleCallbacks
 */
class PreferenceItem implements Validatable, PreferencesStorageInterface {

    use PrimaryKey;

    /**
     * @ORM\Column(name="``key`", type="string", unique=true)
     */
    protected $key;

    /**
     * @ORM\Column(type="json")
     */
    protected $contents;

    /**
     * Returns the key of the preferences.
     * @return string
     */
    public function getKey(): string {
        return $this->key;
    }

    /**
     * Sets the key of the preferences.
     * @param string $key
     */
    public function setKey(string $key) {
        $this->key = $key;
    }

    /**
     * Returns the preferences repository.
     * Creates a new repository if one doesn't already exist.
     *
     * @return array
     */
    public function getPreferences(): array {
        return $this->contents;
    }

    /**
     * Sets the preferences repository.
     *
     * @param array $preferences
     * @return void
     */
    public function setPreferences(array $preferences) {
        $this->contents = $preferences;
    }

    /**
     * Returns an array of validation rules used to validate the model.
     *
     * @return array
     */
    public function getValidationRules() {
        return [
            'key' => [
                'required',
                'alphaDot'
            ]
        ];
    }

}
