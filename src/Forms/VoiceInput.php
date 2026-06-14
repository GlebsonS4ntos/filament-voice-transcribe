<?php

namespace GlebsonS4ntos\FilamentVoiceTranscribe\Forms;

use Closure;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Forms\Components\Field;
use GlebsonS4ntos\FilamentVoiceTranscribe\Enums\VoiceButtonPositionEnum;

class VoiceInput extends Field
{
    use HasPlaceholder;

    protected string $view = 'filament-voice-transcribe::forms.voice-input';

    protected string | Closure $language = 'en-US';

    /**
     * @var scalar | Closure | null
     */
    protected $maxValue = null;

    /**
     * @var scalar | Closure | null
     */
    protected $minValue = null;

    protected VoiceButtonPositionEnum | Closure $positionButton = VoiceButtonPositionEnum::Suffix;

    protected bool | Closure $inlineButton = true;

    //  ->suffixIcon('heroicon-s-microphone'), //heroicon-o-microphone

    public function voiceLanguage(string | Closure $language)
    {
        $this->language = $language;

        return $this;
    }

    public function getVoiceLanguage(): string
    {
        /** @var string $language */
        $language = $this->evaluate($this->language);

        return $language;
    }

        /**
     * @param  scalar | Closure | null  $value
     */
    public function maxValue($value): static
    {
        $this->maxValue = $value;

        $this->rule(static function (VoiceInput $component): string {
            $value = $component->getMaxValue();

            return "max:{$value}";
        }, static fn (VoiceInput $component): bool => filled($component->getMaxValue()));

        return $this;
    }

    /**
     * @param  scalar | Closure | null  $value
     */
    public function minValue($value): static
    {
        $this->minValue = $value;

        $this->rule(static function (VoiceInput $component): string {
            $value = $component->getMinValue();

            return "min:{$value}";
        }, static fn (VoiceInput $component): bool => filled($component->getMinValue()));

        return $this;
    }

    public function getMaxValue(): mixed
    {
        return $this->evaluate($this->maxValue);
    }

    public function getMinValue(): mixed
    {
        return $this->evaluate($this->minValue);
    }

    public function prefixVoiceButton() : static
    {
        $this->positionButton = VoiceButtonPositionEnum::Prefix;

        return $this;
    }

    public function suffixVoiceButton() : static
    {
        $this->positionButton = VoiceButtonPositionEnum::Suffix;

        return $this;
    }

    public function getVoiceButtonPosition(): VoiceButtonPositionEnum
    {
        return $this->evaluate($this->positionButton);
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
