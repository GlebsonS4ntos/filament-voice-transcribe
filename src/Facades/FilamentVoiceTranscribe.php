<?php

namespace GlebsonS4ntos\FilamentVoiceTranscribe\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \GlebsonS4ntos\FilamentVoiceTranscribe\FilamentVoiceTranscribe
 */
class FilamentVoiceTranscribe extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \GlebsonS4ntos\FilamentVoiceTranscribe\FilamentVoiceTranscribe::class;
    }
}
