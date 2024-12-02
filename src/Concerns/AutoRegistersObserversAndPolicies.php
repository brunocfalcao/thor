<?php

namespace Nidavellir\Thor\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait AutoRegistersObserversAndPolicies
{
    /**
     * Automatically load observers based on the naming convention.
     *
     * @return void
     */
    public function autoRegisterObservers()
    {
        $rootPath = __DIR__.'/../';
        $observersPath = $rootPath.'Observers';

        // Check if the Observers directory exists
        if (! File::exists($observersPath)) {
            return;
        }

        // Get all model files from the default Models directory
        $modelsPath = $rootPath.'Models';
        $modelFiles = File::exists($modelsPath) ? File::allFiles($modelsPath) : [];

        foreach ($modelFiles as $modelFile) {
            $modelClass = $this->getClassFromFile($modelFile);

            // Guess the observer name based on the model class
            $observerClass = 'Nidavellir\\Thor\\Observers\\'.class_basename($modelClass).'Observer';

            // If the observer class exists, register it with the model
            if (class_exists($observerClass)) {
                $modelClass::observe($observerClass);
            }
        }
    }

    /**
     * Automatically load policies based on the naming convention.
     *
     * @return void
     */
    public function autoRegisterPolicies()
    {
        $rootPath = __DIR__.'/../';
        $policiesPath = $rootPath.'Policies';

        // Check if the Policies directory exists
        if (! File::exists($policiesPath)) {
            return;
        }

        // Get all model files from the default Models directory
        $modelsPath = $rootPath.'Models';
        $modelFiles = File::exists($modelsPath) ? File::allFiles($modelsPath) : [];

        foreach ($modelFiles as $modelFile) {
            $modelClass = $this->getClassFromFile($modelFile);

            // Guess the policy name based on the model class
            $policyClass = 'Nidavellir\\Thor\\Policies\\'.class_basename($modelClass).'Policy';

            // If the policy class exists, register it with the model
            if (class_exists($policyClass)) {
                \Gate::policy($modelClass, $policyClass);
            }
        }
    }

    /**
     * Get the fully qualified class name from a given file.
     *
     * @param  \SplFileInfo  $file
     * @return string|null
     */
    protected function getClassFromFile($file)
    {
        $namespace = 'Nidavellir\\Thor\\Models';
        $className = Str::replaceLast('.php', '', $file->getFilename());

        return "{$namespace}\\{$className}";
    }
}
