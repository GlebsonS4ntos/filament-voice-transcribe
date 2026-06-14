@php
    use GlebsonS4ntos\FilamentVoiceTranscribe\Enums\VoiceTextareaButtonPositionEnum;

    $fieldWrapperView = $getFieldWrapperView();
    $extraAttributeBag = $getExtraAttributeBag();
    $isConcealed = $isConcealed();
    $isDisabled = $isDisabled();
    $rows = $getRows();
    $placeholder = $getPlaceholder();
    $shouldAutosize = $shouldAutosize();
    $statePath = $getStatePath();
    $voiceLanguage = $getVoiceLanguage();
    $voiceButtonPosition = $getVoiceButtonPosition();

    $initialHeight = (($rows ?? 2) * 1.5) + 0.75;

    $voiceButtonPositionClass = match ($voiceButtonPosition) {
        VoiceTextareaButtonPositionEnum::TopLeft => 'fi-voice-textarea-btn-top-left',
        VoiceTextareaButtonPositionEnum::TopCenter => 'fi-voice-textarea-btn-top-center',
        VoiceTextareaButtonPositionEnum::TopRight => 'fi-voice-textarea-btn-top-right',
        VoiceTextareaButtonPositionEnum::CenterLeft => 'fi-voice-textarea-btn-center-left',
        VoiceTextareaButtonPositionEnum::CenterRight => 'fi-voice-textarea-btn-center-right',
        VoiceTextareaButtonPositionEnum::BottomLeft => 'fi-voice-textarea-btn-bottom-left',
        VoiceTextareaButtonPositionEnum::BottomCenter => 'fi-voice-textarea-btn-bottom-center',
        VoiceTextareaButtonPositionEnum::BottomRight => 'fi-voice-textarea-btn-bottom-right',
    };

    $voiceButtonInputPaddingClass = match ($voiceButtonPosition) {
        VoiceTextareaButtonPositionEnum::TopLeft,
        VoiceTextareaButtonPositionEnum::CenterLeft,
        VoiceTextareaButtonPositionEnum::BottomLeft => 'fi-voice-textarea-input-has-left-button',
        VoiceTextareaButtonPositionEnum::TopRight,
        VoiceTextareaButtonPositionEnum::CenterRight,
        VoiceTextareaButtonPositionEnum::BottomRight => 'fi-voice-textarea-input-has-right-button',
        VoiceTextareaButtonPositionEnum::TopCenter,
        VoiceTextareaButtonPositionEnum::BottomCenter => 'fi-voice-textarea-input-has-center-button',
    };

    $voiceButtonVerticalPaddingClass = match ($voiceButtonPosition) {
        VoiceTextareaButtonPositionEnum::TopCenter => 'fi-voice-textarea-input-has-top-button',
        VoiceTextareaButtonPositionEnum::BottomCenter => 'fi-voice-textarea-input-has-bottom-button',
        VoiceTextareaButtonPositionEnum::TopLeft,
        VoiceTextareaButtonPositionEnum::TopRight,
        VoiceTextareaButtonPositionEnum::CenterLeft,
        VoiceTextareaButtonPositionEnum::CenterRight,
        VoiceTextareaButtonPositionEnum::BottomLeft,
        VoiceTextareaButtonPositionEnum::BottomRight => null,
    };
@endphp

<x-dynamic-component
    :component="$fieldWrapperView"
    :field="$field"
    class="fi-fo-textarea-wrp"
>
    <div
        class="fi-voice-transcribe fi-voice-textarea"
        x-load
        x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-voice-transcribe', 'glebsons4ntos/filament-voice-transcribe') }}"
        x-data="filamentVoiceTranscribe({
            state: $wire.$entangle(@js($statePath)),
            language: @js($voiceLanguage),
        })"
    >
        <x-filament::input.wrapper
            :disabled="$isDisabled"
            :valid="! $errors->has($statePath)"
            :attributes="
                \Filament\Support\prepare_inherited_attributes($extraAttributeBag)
                    ->class([
                        'fi-fo-textarea',
                        'fi-autosizable' => $shouldAutosize,
                    ])
            "
        >
            <div class="fi-voice-textarea-content" wire:ignore.self style="height: '{{ $initialHeight . 'rem' }}'">
                <textarea
                    x-load
                    x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('textarea', 'filament/forms') }}"
                    x-data="textareaFormComponent({
                                initialHeight: @js($initialHeight),
                                shouldAutosize: @js($shouldAutosize),
                                state: $wire.$entangle(@js($statePath)),
                            })"
                    @if ($shouldAutosize)
                        x-intersect.once="resize()"
                        x-on:resize.window="resize()"
                    @endif
                    x-model="state"
                    @if ($isGrammarlyDisabled())
                        data-gramm="false"
                        data-gramm_editor="false"
                        data-enable-grammarly="false"
                    @endif
                    {{ $getExtraAlpineAttributeBag() }}
                    {{
                        $getExtraInputAttributeBag()
                            ->merge([
                                'autocomplete' => $getAutocomplete(),
                                'autofocus' => $isAutofocused(),
                                'cols' => $getCols(),
                                'disabled' => $isDisabled,
                                'id' => $getId(),
                                'maxlength' => (! $isConcealed) ? $getMaxLength() : null,
                                'minlength' => (! $isConcealed) ? $getMinLength() : null,
                                'placeholder' => filled($placeholder) ? e($placeholder) : null,
                                'readonly' => $isReadOnly(),
                                'required' => $isRequired() && (! $isConcealed),
                                'rows' => $rows,
                                $applyStateBindingModifiers('wire:model') => $statePath,
                            ], escape: false)
                            ->class([
                                'fi-voice-textarea-input',
                                $voiceButtonInputPaddingClass,
                                $voiceButtonVerticalPaddingClass,
                            ])
                    }}
                ></textarea>

                <button
                    type="button"
                    @class([
                        'fi-voice-transcribe-btn',
                        'fi-voice-textarea-btn',
                        $voiceButtonPositionClass,
                    ])
                    x-on:click="startRecording"
                    x-bind:disabled="! isSupported || @js($isDisabled)"
                    x-bind:class="{
                        'fi-voice-transcribe-btn-recording': isRecording,
                        'fi-voice-transcribe-btn-disabled': ! isSupported,
                    }"
                    aria-label="Transcrever por voz"
                >
                    <x-filament::icon
                        icon="heroicon-s-microphone"
                        class="fi-voice-transcribe-btn-icon"
                    />
                </button>
            </div>
        </x-filament::input.wrapper>
    </div>
</x-dynamic-component>
