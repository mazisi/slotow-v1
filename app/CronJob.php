<?php

use App\Models\User;

class CronJob{

  static function db_backup() : void {
    // Artisan::call('backup:run --only-db');
    //return route('/db-auto-backup');

    User::create([
      'name' => 'CronJob',
      'password' => '12345',
      'email' => 'cron@ex.com'
    ]);
  }

}
CronJob::db_backup();