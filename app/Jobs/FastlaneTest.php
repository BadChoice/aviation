<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class FastlaneTest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $folder;

    public function __construct($folder)
    {
        $this->folder = $folder;
    }

    public function handle()
    {
        $process = new Process("
            cd ../storage/app/builds/{$this->folder} &&
            export LC_ALL=en_US.UTF-8 &&
            export LANG=en_US.UTF-8 &&
            export PATH='/usr/local/bin:/usr/bin:\$PATH' &&
            bundle install --path=vendor/bundle &&
            bundle exec fastlane tests"
        );
        $process->run();

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
