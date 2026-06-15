@php
    use Illuminate\Support\HtmlString;
    use Illuminate\Support\Js;
    use Illuminate\View\ComponentAttributeBag;

    $state = $getState();
    $placeholder = $getPlaceholder();
    $voiceLanguage = $getVoiceLanguage();
    $icon = 'heroicon-s-speaker-wave';

    $prefixVoiceButtonPosition = $getVoiceButtonPosition() === \GlebsonS4ntos\FilamentVoiceTranscribe\Enums\VoiceButtonPositionEnum::Prefix;
    $suffixVoiceButtonPosition = $getVoiceButtonPosition() === \GlebsonS4ntos\FilamentVoiceTranscribe\Enums\VoiceButtonPositionEnum::Suffix;
    $inlineButton = $isInlineButton();
    $speakableText = filled($state) ? (string) $state : '';

    $isCopyable = filled($state) && $entry->isCopyable($state);
    $copyableStateJs = $isCopyable
        ? Js::from($entry->getCopyableState($state) ?? $entry->formatState($state))
        : null;
    $copyMessageJs = $isCopyable
        ? Js::from($entry->getCopyMessage($state))
        : null;
    $copyMessageDurationJs = $isCopyable
        ? Js::from($entry->getCopyMessageDuration($state))
        : null;

    $textAttributes = (new ComponentAttributeBag)
        ->class([
            'fi-in-text',
            'fi-voice-speak-text',
            'fi-copyable' => $isCopyable,
        ])
        ->merge([
            'x-on:click' => $isCopyable
                ? new HtmlString(<<<JS
                window.navigator.clipboard.writeText({$copyableStateJs})
                \$tooltip({$copyMessageJs}, {
                    theme: \$store.theme,
                    timeout: {$copyMessageDurationJs},
                })
                JS)
                : null,
        ], escape: false);
@endphp

<x-filament-infolists::entry-wrapper :entry="$entry">
    <div
        class="fi-voice-speak"
        x-load
        x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-voice-transcribe', 'glebsons4ntos/filament-voice-transcribe') }}"
        x-data="filamentVoiceTranscribe({
            state: @js($speakableText),
            language: @js($voiceLanguage),
        })"
    >
        <div class="fi-voice-speak-layout">
            @if (! $inlineButton && $prefixVoiceButtonPosition)
                <div class="fi-voice-speak-side fi-voice-speak-side-prefix">
                    <button
                        type="button"
                        class="fi-voice-transcribe-btn"
                        x-on:click="speakText()"
                        x-bind:disabled="! isSpeechSynthesisSupported || ! state"
                        x-bind:class="{
                            'fi-voice-transcribe-btn-recording': isSpeaking,
                            'fi-voice-transcribe-btn-disabled': ! isSpeechSynthesisSupported || ! state,
                        }"
                        aria-label="Falar texto"
                    >
                        <x-filament::icon
                            :icon="$icon"
                            class="fi-voice-transcribe-btn-icon"
                        />
                    </button>
                </div>
            @endif

            <div class="fi-voice-speak-content">
                @if ($inlineButton && $prefixVoiceButtonPosition)
                    <button
                        type="button"
                        class="fi-voice-transcribe-btn"
                        x-on:click="speakText()"
                        x-bind:disabled="! isSpeechSynthesisSupported || ! state"
                        x-bind:class="{
                            'fi-voice-transcribe-btn-recording': isSpeaking,
                            'fi-voice-transcribe-btn-disabled': ! isSpeechSynthesisSupported || ! state,
                        }"
                        aria-label="Falar texto"
                    >
                        <x-filament::icon
                            :icon="$icon"
                            class="fi-voice-transcribe-btn-icon"
                        />
                    </button>
                @endif

                @if (filled($state))
                    <div {{ $textAttributes }}>
                        {{ $state }}
                    </div>
                @elseif (filled($placeholder))
                    <p class="fi-in-placeholder">
                        {{ $placeholder }}
                    </p>
                @endif

                @if ($inlineButton && $suffixVoiceButtonPosition)
                    <button
                        type="button"
                        class="fi-voice-transcribe-btn"
                        x-on:click="speakText()"
                        x-bind:disabled="! isSpeechSynthesisSupported || ! state"
                        x-bind:class="{
                            'fi-voice-transcribe-btn-recording': isSpeaking,
                            'fi-voice-transcribe-btn-disabled': ! isSpeechSynthesisSupported || ! state,
                        }"
                        aria-label="Falar texto"
                    >
                        <x-filament::icon
                            :icon="$icon"
                            class="fi-voice-transcribe-btn-icon"
                        />
                    </button>
                @endif
            </div>

            @if (! $inlineButton && $suffixVoiceButtonPosition)
                <div class="fi-voice-speak-side fi-voice-speak-side-suffix">
                    <button
                        type="button"
                        class="fi-voice-transcribe-btn"
                        x-on:click="speakText()"
                        x-bind:disabled="! isSpeechSynthesisSupported || ! state"
                        x-bind:class="{
                            'fi-voice-transcribe-btn-recording': isSpeaking,
                            'fi-voice-transcribe-btn-disabled': ! isSpeechSynthesisSupported || ! state,
                        }"
                        aria-label="Falar texto"
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
</x-filament-infolists::entry-wrapper>
