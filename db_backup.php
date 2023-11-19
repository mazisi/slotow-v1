<?php
use App\Models\User;


  function db_backup() : void {
    // Artisan::call('backup:run --only-db');
    //return route('/db-auto-backup');

    User::create([
      'name' => 'CronJob',
      'password' => '12345',
      'email' => 'cron@ex.com'
    ]);
  }


db_backup();