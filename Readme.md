# Forum 
Travis CI: [![Build Status](https://travis-ci.org/Reddok/laravel-forum.svg?branch=master)](https://travis-ci.org/Reddok/laravel-forum)

Style CI: [![Build Status](https://github.styleci.io/repos/167816228/shield?branch=master)](https://github.styleci.io/repos/167816228)

This is forum that was built on Laravel and Vue.js.

## Installation

### Prerequisites

* To run this project, you must have PHP 7 installed.
* You should setup a host on your web server for your local domain. For this you could also configure Laravel Homestead or Valet. 
* If you want use Redis as your cache driver you need to install the Redis Server. You can either use homebrew on a Mac or compile from source (https://redis.io/topics/quickstart). 

### Step 1

Begin by cloning this repository to your machine, and installing all Composer & NPM dependencies.

```bash
git clone git@github.com:Reddok/laravel-forum.git
cd laravel-forum && composer install && npm install
php artisan forum:install
npm run dev
```

### Step 2

Next, boot up a server and visit your forum. If using a tool like Laravel Valet, of course the URL will default to `http://forum.test`. 

1. Visit: `http://forum.test/register` to register a new forum account.
2. Edit `config/forum.php`, and add any email address that should be marked as an administrator.
3. Visit: `http://forum.test/admin/channels` to seed your forum with one or more channels.