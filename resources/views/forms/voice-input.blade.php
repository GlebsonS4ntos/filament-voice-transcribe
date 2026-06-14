@php
    $fieldWrapperView = $getFieldWrapperView();
    $extraAttributeBag = $getExtraAttributeBag();

    $id = $getId();
    $statePath = $getStatePath();

    $isDisabled = $isDisabled();

    $placeholder = $getPlaceholder();

    $icon = 'heroicon-s-microphone';

    $prefixVoiceButtonPosition = $getVoiceButtonPosition() === \GlebsonS4ntos\FilamentVoiceTranscribe\Enums\VoiceButtonPositionEnum::Prefix;
    $suffixVoiceButtonPosition = $getVoiceButtonPosition() === \GlebsonS4ntos\FilamentVoiceTranscribe\Enums\VoiceButtonPositionEnum::Suffix;
    $voiceLanguage = $getVoiceLanguage();
    $inlineButton = $isInlineButton();

    $inputAttributes = $getExtraAttributeBag()
        ->merge([
            'id' => $id,
            'disabled' => $isDisabled,
            'maxlength' => $getMaxValue(),
            'minlength' => $getMinValue(),
            'placeholder' => filled($placeholder) ? e($placeholder) : null,
            'required' => $isRequired(),
            $applyStateBindingModifiers('wire:model') => $statePath,
        ])
        ->class([
            'fi-input',
            'fi-input-has-inline-prefix' => $inlineButton && $prefixVoiceButtonPosition,
            'fi-input-has-inline-suffix' => $inlineButton && $suffixVoiceButtonPosition,
        ]);

@endphp

<x-dynamic-component
    :component="$fieldWrapperView"
    :field="$field"
>
    <div
        class="fi-voice-transcribe"
        x-load
        x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-voice-transcribe', 'glebsons4ntos/filament-voice-transcribe') }}"
        x-data="filamentVoiceTranscribe({
            state: $wire.$entangle(@js($statePath)),
            language: @js($voiceLanguage),
        })"
    >
        <div class="fi-voice-transcribe-layout" style="display: flex; align-items: center; width: 100%;">
            @if (! $inlineButton && $prefixVoiceButtonPosition)
                <div class="fi-voice-transcribe-side fi-voice-transcribe-side-prefix" style="display: flex; align-items: center; margin-right: 1.25rem;">
                    <button
                        type="button"
                        class="fi-voice-transcribe-btn"
                        style="display: inline-flex; align-items: center; justify-content: center; width: 2rem; height: 2rem; border: 0; border-radius: 9999px; background: transparent;"
                        x-on:click="startRecording"
                        x-bind:disabled="! isSupported"
                        x-bind:class="{
                            'fi-voice-transcribe-btn-recording': isRecording,
                            'fi-voice-transcribe-btn-disabled': ! isSupported,
                        }"
                        @disabled($isDisabled)
                        aria-label="Transcrever por voz"
                    >
                        <x-filament::icon
                            :icon="$icon"
                            class="fi-voice-transcribe-btn-icon"
                        />
                    </button>
                </div>
            @endif

            <x-filament::input.wrapper
                :disabled="$isDisabled"
                :valid="! $errors->has($statePath)"
                :attributes="
                    \Filament\Support\prepare_inherited_attributes($extraAttributeBag)
                        ->class([
                            'fi-fo-voice-input',
                        ])
                        ->style([
                            'flex: 1 1 auto',
                            'min-width: 0',
                        ])
                "
            >
                <div class="fi-voice-transcribe-inline" style="display: flex; align-items: center; width: 100%;">
                    @if ($inlineButton && $prefixVoiceButtonPosition)
                        <button
                            type="button"
                            class="fi-voice-transcribe-btn"
                            style="display: inline-flex; align-items: center; justify-content: center; width: 2rem; height: 2rem; border: 0; border-radius: 9999px; background: transparent;"
                            x-on:click="startRecording"
                            x-bind:disabled="! isSupported"
                            x-bind:class="{
                                'fi-voice-transcribe-btn-recording': isRecording,
                                'fi-voice-transcribe-btn-disabled': ! isSupported,
                            }"
                            @disabled($isDisabled)
                            aria-label="Transcrever por voz"
                        >
                            <x-filament::icon
                                :icon="$icon"
                                class="fi-voice-transcribe-btn-icon"
                            />
                        </button>
                    @endif

                    <input {{ $inputAttributes }} />

                    @if ($inlineButton && $suffixVoiceButtonPosition)
                        <button
                            type="button"
                            class="fi-voice-transcribe-btn"
                            style="display: inline-flex; align-items: center; justify-content: center; width: 2rem; height: 2rem; border: 0; border-radius: 9999px; background: transparent;"
                            x-on:click="startRecording"
                            x-bind:disabled="! isSupported"
                            x-bind:class="{
                                'fi-voice-transcribe-btn-recording': isRecording,
                                'fi-voice-transcribe-btn-disabled': ! isSupported,
                            }"
                            @disabled($isDisabled)
                            aria-label="Transcrever por voz"
                        >
                            <x-filament::icon
                                :icon="$icon"
                                class="fi-voice-transcribe-btn-icon"
                            />
                        </button>
                    @endif
                </div>
            </x-filament::input.wrapper>

            @if (! $inlineButton && $suffixVoiceButtonPosition)
                <div class="fi-voice-transcribe-side fi-voice-transcribe-side-suffix" style="display: flex; align-items: center; margin-left: 1.25rem;">
                    <button
                        type="button"
                        class="fi-voice-transcribe-btn"
                        style="display: inline-flex; align-items: center; justify-content: center; width: 2rem; height: 2rem; border: 0; border-radius: 9999px; background: transparent;"
                        x-on:click="startRecording"
                        x-bind:disabled="! isSupported"
                        x-bind:class="{
                            'fi-voice-transcribe-btn-recording': isRecording,
                            'fi-voice-transcribe-btn-disabled': ! isSupported,
                        }"
                        @disabled($isDisabled)
                        aria-label="Transcrever por voz"
                    >
                        <x-filament::icon
                            :icon="$icon"
                            class="fi-voice-transcribe-btn-icon"
                        />
                    </button>
                </div>
            @endif
        </div>
    </div>
</x-dynamic-component>
