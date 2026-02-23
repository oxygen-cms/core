<?php

namespace Oxygen\Core\Preferences\Loader;

use Illuminate\Support\Arr;
use Oxygen\Data\Exception\InvalidEntityException;
use Oxygen\Core\Preferences\ChainedStore;
use Oxygen\Data\Exception\NoResultException;
use Oxygen\Core\Preferences\Loader\Database\PreferenceItem;
use Oxygen\Core\Preferences\PreferenceNotFoundException;
use Oxygen\Core\Preferences\PreferencesSettingInterface;
use Oxygen\Core\Preferences\PreferencesStorageInterface;
use Oxygen\Core\Preferences\PreferencesStoreInterface;

class DatabaseLoader implements LoaderInterface, PreferencesSettingInterface {

    /**
     * Config repository to use.
     *
     * @var PreferenceRepositoryInterface
     */
    protected $repository;

    /**
     * Key
     *
     * @var string
     */
    protected $key;

    /**
     * @var ChainedStore
     */
    private $cachedRepository;

    /**
     * @var PreferencesStorageInterface
     */
    private $preferenceItem;

    /**
     * @var PreferencesStorageInterface|null
     */
    private $fallback;

    /**
     * Constructs the ConfigLoader.
     *
     * @param PreferenceRepositoryInterface $repository
     * @param string $key
     * @param PreferencesStorageInterface|null $fallback
     */
    public function __construct(PreferenceRepositoryInterface $repository, string $key, PreferencesStorageInterface $fallback = null) {
        $this->repository = $repository;
        $this->key = $key;
        $this->fallback = $fallback;
    }

    /**
     * Loads the preferences and returns the repository.
     *
     * @return PreferencesStoreInterface
     * @throws PreferenceNotFoundException
     */
    public function load(): PreferencesStoreInterface {
        if($this->cachedRepository === null) {
            try {
                $this->preferenceItem = $this->repository->findByKey($this->key);
                $chain = function() {
                        yield $this->preferenceItem->getPreferences();
                        if ($this->fallback !== null) {
                            yield $this->fallback->getPreferences();
                        }
                    };
                $this->cachedRepository = new ChainedStore($chain, $this);
            } catch(NoResultException $e) {
                throw new PreferenceNotFoundException('Preference Key ' . $this->key . ' Not Found In Database', 0, $e);
            }
        }

        return $this->cachedRepository;
    }

    /**
     * Stores the preferences.
     *
     * @return void
     * @throws InvalidEntityException if the preferences were invalid
     */
    public function store() {
        if($this->cachedRepository !== null) {
            $this->repository->persist($this->preferenceItem);
        }
    }

    /**
     * Sets the preferences value.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value) {
        $prefs = $this->preferenceItem->getPreferences();
        Arr::set($prefs, $key, $value);
        $this->preferenceItem->setPreferences($prefs);
    }
}
