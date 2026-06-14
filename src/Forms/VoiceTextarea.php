<?php

namespace GlebsonS4ntos\FilamentVoiceTranscribe\Forms;

use Closure;
use Filament\Forms\Components\Textarea;
use GlebsonS4ntos\FilamentVoiceTranscribe\Enums\VoiceTextareaButtonPositionEnum;

class VoiceTextarea extends Textarea
{
    protected string $view = 'filament-voice-transcribe::forms.voice-textarea';

    protected string | Closure $language = 'en-US';

    protected VoiceTextareaButtonPositionEnum | Closure $voiceButtonPosition = VoiceTextareaButtonPositionEnum::BottomRight;

    public function voiceLanguage(string | Closure $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getVoiceLanguage(): string
    {
        return (string) $this->evaluate($this->language);
    }

    public function voiceButtonPosition(VoiceTextareaButtonPositionEnum | Closure $position): static
    {
        $this->voiceButtonPosition = $position;

        return $this;
    }

    public function topLeftVoiceButton(): static
    {
        return $this->voiceButtonPosition(VoiceTextareaButtonPositionEnum::TopLeft);
    }

    public function topCenterVoiceButton(): static
    {
        return $this->voiceButtonPosition(VoiceTextareaButtonPositionEnum::TopCenter);
    }

    public function topRightVoiceButton(): static
    {
        return $this->voiceButtonPosition(VoiceTextareaButtonPositionEnum::TopRight);
    }

    public function centerLeftVoiceButton(): static
    {
        return $this->voiceButtonPosition(VoiceTextareaButtonPositionEnum::CenterLeft);
    }

    public function centerRightVoiceButton(): static
    {
        return $this->voiceButtonPosition(VoiceTextareaButtonPositionEnum::CenterRight);
    }

    public function bottomLeftVoiceButton(): static
    {
        return $this->voiceButtonPosition(VoiceTextareaButtonPositionEnum::BottomLeft);
    }

    public function bottomCenterVoiceButton(): static
    {
        return $this->voiceButtonPosition(VoiceTextareaButtonPositionEnum::BottomCenter);
    }

    public function bottomRightVoiceButton(): static
    {
        return $this->voiceButtonPosition(VoiceTextareaButtonPositionEnum::BottomRight);
    }

    public function getVoiceButtonPosition(): VoiceTextareaButtonPositionEnum
    {
        /** @var VoiceTextareaButtonPositionEnum $position */
        $position = $this->evaluate($this->voiceButtonPosition);

        return $position;
    }
}
