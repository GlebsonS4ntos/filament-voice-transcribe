<?php

namespace GlebsonS4ntos\FilamentVoiceTranscribe;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentVoiceTranscribeServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-voice-transcribe';

    public static string $viewNamespace = 'filament-voice-transcribe';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews(static::$viewNamespace);
    }

    public function packageBooted(): void
    {
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName(),
        );
    }

    protected function getAssetPackageName(): string
    {
        return 'glebsons4ntos/filament-voice-transcribe';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            AlpineComponent::make('filament-voice-transcribe', __DIR__ . '/../resources/dist/filament-voice-transcribe.js'),
            Css::make('filament-voice-transcribe-styles', __DIR__ . '/../resources/dist/filament-voice-transcribe.css'),
        ];
    }
}
