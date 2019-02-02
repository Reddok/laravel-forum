<?php

use App\Reply;
use App\Thread;
use App\Channel;
use App\Activity;
use App\Favorite;
use App\ThreadSubscription;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
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
