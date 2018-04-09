<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CloneRepo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $repo;
    protected $branch;
    protected $folder;

    public function __construct($repo, $branch, $folder)
    {
        $this->repo     = $repo;
        $this->branch   = $branch;
        $this->folder = $folder;
    }

    public function handle()
    {
        $repoName = $this->getRepoName();
        $process = new Process("
            cd ../storage/app/builds &&
            ssh-agent bash -c 'ssh-add /Volumes/Dades/badchoice/.ssh/badchoice; git clone {$this->repo}' &&
            mv {$repoName} {$this->folder} &&
            cd {$this->folder} 
            git checkout {$this->branch}"
        );
        $process->run();

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    private function getRepoName()
    {
        preg_match('/\/(.*).git/', $this->repo, $matches);
        return $matches[1];
    }
}
