<?php

namespace GlebsonS4ntos\FilamentVoiceTranscribe\Infolists;

use Closure;
use Filament\Infolists\Components\TextEntry;
use GlebsonS4ntos\FilamentVoiceTranscribe\Enums\VoiceButtonPositionEnum;

class VoiceTextEntry extends TextEntry
{
    protected string $view = 'filament-voice-transcribe::infolists.voice-text-entry';

    protected string | Closure $language = 'en-US';

    protected VoiceButtonPositionEnum | Closure $positionButton = VoiceButtonPositionEnum::Prefix;

    protected bool | Closure $inlineButton = true;

    public function voiceLanguage(string | Closure $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getVoiceLanguage(): string
    {
        return (string) $this->evaluate($this->language);
    }

    public function prefixVoiceButton(): static
    {
        $this->positionButton = VoiceButtonPositionEnum::Prefix;

        return $this;
    }

    public function suffixVoiceButton(): static
    {
        $this->positionButton = VoiceButtonPositionEnum::Suffix;

        return $this;
    }

    public function getVoiceButtonPosition(): VoiceButtonPositionEnum
    {
        /** @var VoiceButtonPositionEnum $position */
        $position = $this->evaluate($this->positionButton);

        return $position;
    }

    public function inlineVoiceButton(bool | Closure $isInlineButton): static
    {
        $this->inlineButton = $isInlineButton;

        return $this;
    }

    public function isInlineButton(): bool
    {
        return (bool) $this->evaluate($this->inlineButton);
    }
}
