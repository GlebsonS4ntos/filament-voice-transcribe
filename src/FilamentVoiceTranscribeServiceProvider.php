<?php

namespace GlebsonS4ntos\FilamentVoiceTranscribe;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use GlebsonS4ntos\FilamentVoiceTranscribe\Commands\FilamentVoiceTranscribeCommand;
use GlebsonS4ntos\FilamentVoiceTranscribe\Testing\TestsFilamentVoiceTranscribe;

class FilamentVoiceTranscribeServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-voice-transcribe';

    public static string $viewNamespace = 'filament-voice-transcribe';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('glebsons4ntos/filament-voice-transcribe');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-voice-transcribe/{$file->getFilename()}"),
                ], 'filament-voice-transcribe-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentVoiceTranscribe);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'glebsons4ntos/filament-voice-transcribe';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filament-voice-transcribe', __DIR__ . '/../resources/dist/components/filament-voice-transcribe.js'),
            // Css::make('filament-voice-transcribe-styles', __DIR__ . '/../resources/dist/filament-voice-transcribe.css'),
            // Js::make('filament-voice-transcribe-scripts', __DIR__ . '/../resources/dist/filament-voice-transcribe.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentVoiceTranscribeCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_filament-voice-transcribe_table',
        ];
    }
}
