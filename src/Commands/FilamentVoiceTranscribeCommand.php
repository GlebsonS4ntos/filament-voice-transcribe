<?php

namespace GlebsonS4ntos\FilamentVoiceTranscribe\Commands;

use Illuminate\Console\Command;

class FilamentVoiceTranscribeCommand extends Command
{
    public $signature = 'filament-voice-transcribe';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
