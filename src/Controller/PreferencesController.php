<?php

namespace Oxygen\Core\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;
use Oxygen\Core\Controller\Controller;
use Oxygen\Core\Permissions\PermissionsInterface;
use Oxygen\Core\Preferences\FallbackStoreInterface;
use Oxygen\Core\Preferences\PreferenceNotFoundException;
use Oxygen\Core\Preferences\PreferencesManager;
use Oxygen\Core\Http\Notification;
use Oxygen\Core\Preferences\Schema;

class PreferencesController extends Controller {

    private PreferencesManager $preferences;
    private PermissionsInterface $permissions;
    private Factory $validationFactory;

    /**
     * Constructs the AuthController.
     *
     * @param PreferencesManager $preferences
     * @param PermissionsInterface $permissions
     * @param Factory $validationFactory
     */
    public function __construct(PreferencesManager $preferences, PermissionsInterface $permissions, Factory $validationFactory) {
        $this->preferences = $preferences;
        $this->permissions = $permissions;
        $this->validationFactory = $validationFactory;
    }

    /**
     * Form to update a preferences.
     *
     * @param string $key
     * @return JsonResponse
     * @throws PreferenceNotFoundException
     */
    public function getValue(string $key): JsonResponse {
        $errorResponse = $this->checkHasPermissions($key);
        if($errorResponse !== null) {
            return $errorResponse;
        }

        $schema = $this->preferences
            ->getSchema($this->getPreferencesGroup($key));

        $field = $schema->getField($this->getPreferencesField($key));

        return response()->json(
            array_merge([
                'options' => $field->getOptions(),
                'status' => Notification::SUCCESS
            ], $this->getValues($schema, $key))
        );
    }

    private function getValues(Schema $schema, string $key) {
        $field = $this->getPreferencesField($key);
        $repository = $schema->getRepository();
        $value = $repository->getOrDefault($field, null);
        $fallbackValue = null;
        $primaryValue = $value;
        if($repository instanceof FallbackStoreInterface) {
            $fallbackValue = $repository->getFallback($field);
            $primaryValue = $repository->getPrimary($field);
        }

        return [
            'primaryValue' => $primaryValue,
            'value' => $value,
            'fallbackValue' => $fallbackValue
        ];
    }

    /**
     * Form to update a preferences.
     *
     * @param string $key
     * @param Request $request
     * @return JsonResponse
     * @throws PreferenceNotFoundException
     */
    public function postCheckIsValueValid(string $key, Request $request): JsonResponse {
        $errorResponse = $this->checkHasPermissions($key);
        if($errorResponse !== null) {
            return $errorResponse;
        }

        $schema = $this->preferences
            ->getSchema($this->getPreferencesGroup($key));

        $errorResponse = $this->checkIsValueValid($schema, $key, $request->input('value'));
        if($errorResponse !== null) {
            return $errorResponse;
        }

        return response()->json([
            'valid' => true,
            'status' => Notification::SUCCESS
        ]);
    }

    /**
     * Form to update a preferences.
     *
     * @param string $key
     * @param Request $request
     * @return JsonResponse
     * @throws PreferenceNotFoundException
     */
    public function putUpdateValue(string $key, Request $request): JsonResponse {
        $errorResponse = $this->checkHasPermissions($key);
        if($errorResponse !== null) {
            return $errorResponse;
        }

        $schema = $this->preferences
            ->getSchema($this->getPreferencesGroup($key));

        $value = $request->input('value');

        if(!$this->hasFallback($schema, $key) || $value !== null) {
            $errorResponse = $this->checkIsValueValid($schema, $key, $value);
            if($errorResponse !== null) {
                return $errorResponse;
            }
        }

        $schema
            ->getRepository()
            ->set($this->getPreferencesField($key), $value);
        $schema->storeRepository();

        return response()->json(
            array_merge([
                'content' => 'Preference updated',
                'status' => Notification::SUCCESS
            ], $this->getValues($schema, $key))
        );
    }

    /**
     * @param Schema $schema
     * @param string $key
     * @return bool true if there is a fallback for this value
     */
    private function hasFallback(Schema $schema, string $key) {
        $repository = $schema->getRepository();
        return $repository instanceof FallbackStoreInterface && $repository->getFallback($this->getPreferencesField($key)) !== null;
    }

    /**
     * @param string $key
     * @return JsonResponse|null null if permissions were satisfied, a JsonResponse if permissions insufficient
     */
    private function checkHasPermissions(string $key) {
        if(!$this->hasPermissions($key)) {
            return response()->json([
                'content' => 'Insufficient permissions to retrieve these preferences',
                'permissions' => false,
                'status' => Notification::SUCCESS
            ]);
        }
        return null;
    }

    private function hasPermissions(string $prefsKey) {
        $requiredKey = 'preferences.' . str_replace('.', '_', $this->getPreferencesGroup($prefsKey));
        return $this->permissions->has($requiredKey);
    }

    /**
     * @param Schema $schema
     * @param string $key
     * @param mixed $value
     * @return JsonResponse|null
     */
    private function checkIsValueValid(Schema $schema, string $key, $value): ?JsonResponse {
        $validationRules = $schema->getValidationRules();
        $rules = [];
        if(isset($validationRules[$this->getPreferencesField($key)])) {
            $rules = $validationRules[$this->getPreferencesField($key)];
        }

        $validator = $this->validationFactory->make([ 'value' => $value ], [ 'value' => $rules ]);
        if($validator->fails()) {
            return response()->json([
                'valid' => false,
                'reason' => $validator->messages()->first(),
                'status' => Notification::SUCCESS
            ]);
        }
        return null;
    }

    private function getPreferencesGroup(string $key) {
        $keyParts = explode('::', $key);
        return $keyParts[0];
    }

    private function getPreferencesField(string $key) {
        $keyParts = explode('::', $key);
        return $keyParts[1];
    }

}
