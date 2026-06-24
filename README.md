# Filament Voice Transcribe

[![Latest Version on Packagist](https://img.shields.io/packagist/v/glebsons4ntos/filament-voice-transcribe.svg?style=flat-square)](https://packagist.org/packages/glebsons4ntos/filament-voice-transcribe)
[![Total Downloads](https://img.shields.io/packagist/dt/glebsons4ntos/filament-voice-transcribe.svg?style=flat-square)](https://packagist.org/packages/glebsons4ntos/filament-voice-transcribe)

Filament Voice Transcribe adds voice-powered components to Filament forms and infolists.

Use it to transcribe speech into form fields with the browser Speech Recognition API, or to read infolist text aloud with the browser Speech Synthesis API.

## Requirements

- PHP 8.2+
- Laravel with Filament 4
- A browser with Web Speech API support

## Installation

Install the package via Composer:

```bash
composer require glebsons4ntos/filament-voice-transcribe
```

Publish the Filament assets:

```bash
php artisan filament:assets
```

You should run this command again after updating the package so Filament can publish the latest JavaScript and CSS assets.

## Usage

### Voice Input

Use `VoiceInput` when you need a regular text input with a voice button.

```php
use GlebsonS4ntos\FilamentVoiceTranscribe\Forms\VoiceInput;

VoiceInput::make('name')
    ->voiceLanguage('pt-BR');
```

You may place the voice button before or after the input:

```php
VoiceInput::make('name')
    ->voiceLanguage('pt-BR')
    ->prefixVoiceButton();

VoiceInput::make('name')
    ->voiceLanguage('pt-BR')
    ->suffixVoiceButton();
```

The button can also be rendered outside the input wrapper:

```php
VoiceInput::make('name')
    ->voiceLanguage('pt-BR')
    ->inlineVoiceButton(false);
```

### Voice Textarea

Use `VoiceTextarea` for longer text.

```php
use GlebsonS4ntos\FilamentVoiceTranscribe\Forms\VoiceTextarea;

VoiceTextarea::make('description')
    ->voiceLanguage('pt-BR')
    ->rows(5);
```

The voice button can be positioned around the textarea:

```php
VoiceTextarea::make('description')
    ->voiceLanguage('pt-BR')
    ->bottomRightVoiceButton();
```

Available positions:

```php
->topLeftVoiceButton()
->topCenterVoiceButton()
->topRightVoiceButton()
->centerLeftVoiceButton()
->centerRightVoiceButton()
->bottomLeftVoiceButton()
->bottomCenterVoiceButton()
->bottomRightVoiceButton()
```

### Voice Text Entry

Use `VoiceTextEntry` in infolists when you want to display text and let the user listen to it.

```php
use GlebsonS4ntos\FilamentVoiceTranscribe\Infolists\VoiceTextEntry;

VoiceTextEntry::make('description')
    ->voiceLanguage('pt-BR');
```

It extends Filament's `TextEntry`, so you can combine it with common text entry methods:

```php
VoiceTextEntry::make('description')
    ->voiceLanguage('pt-BR')
    ->copyable();
```

You may also move the voice button:

```php
VoiceTextEntry::make('description')
    ->voiceLanguage('pt-BR')
    ->suffixVoiceButton();
```

## Language

Set the browser speech language with `voiceLanguage()`:

```php
->voiceLanguage('pt-BR')
->voiceLanguage('en-US')
->voiceLanguage('es-ES')
```

The value should be a valid BCP 47 language tag supported by the browser and operating system.

## Browser Support

This package uses the browser Web Speech API, so support depends on the user's browser and device.

- Speech recognition is not available in every browser.
- Voice transcription is automatically disabled when the browser does not support the Speech Recognition API.
- Speech synthesis voices depend on the operating system and installed voices.
- If a language voice is not installed, the browser may fall back to a default voice.

## Credits

- [Glebson Santos](https://github.com/GlebsonS4ntos)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
