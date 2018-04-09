<?php

namespace App\Http\Controllers;
use App\Jobs\CloneRepo;
use App\Jobs\FastlaneTest;
use Carbon\Carbon;

class TestController extends Controller
{
    public function test()
    {
//        $timestamp = Carbon::now()->format('YmdHis');
//        CloneRepo::dispatch('git@bitbucket.org:revo-pos/revo-retail-app.git', 'dev', $timestamp);
//        FastlaneTest::dispatch($timestamp);
        FastlaneTest::dispatch("20180409170311");
    }
}
