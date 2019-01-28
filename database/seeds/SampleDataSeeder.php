<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Channel;
use App\Thread;
use App\Reply;
use App\ThreadSubscription;
use App\Activity;
use App\Favorite;

class SampleDataSeeder extends Seeder {

    public function run()
    {
        $this->channels();
        $this->threads();
    }

    protected function channels()
    {
        Channel::truncate();
        factory(Channel::class, 10)
            ->create();
    }

    protected function threads()
    {
        Thread::truncate();
        Reply::truncate();
        ThreadSubscription::truncate();
        Activity::truncate();
        Favorite::truncate();

        factory(Thread::class, 50)
            ->create();
    }
}