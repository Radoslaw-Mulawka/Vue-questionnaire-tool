<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Connectors\MySqlConnector;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class QuickStartDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:quickstartdb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Data Base from sql file.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

      $options = [
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => '',
        'username' => 'tell_us_build',
        'password' => 'ZAQ!2wsx',
      ];

      $connector = new MySqlConnector();
      $defOptions = $connector->connect($options);

      // TODO : Należy dodać sprawdzanie danych nazwy użytkownika, nazwę bazy danych, hasło
      $databaseName = Config::get('database.connections.'.Config::get('database.default').'.database');
      $databaseUsername = Config::get('database.connections.'.Config::get('database.default').'.username');
      $databasePassword = Config::get('database.connections.'.Config::get('database.default').'.password');
      $databaseHost = Config::get('database.connections.'.Config::get('database.default').'.host');

      $createDB =  $defOptions->query('CREATE DATABASE IF NOT EXISTS '.$databaseName);
      $createUser =  $defOptions->query("CREATE USER IF NOT EXISTS '".$databaseUsername."'@'".$databaseHost."' IDENTIFIED WITH mysql_native_password BY '".$databasePassword."'");
      $privileges =  $defOptions->query("GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, ALTER, EVENT, SHOW VIEW ON " .$databaseName.".* TO '".$databaseUsername."'@'".$databaseHost."'");

      $path = 'database/quickstart.sql';

      DB::unprepared(file_get_contents($path));

      $this->info("Successful! Data Base $databaseName created.");
      $this->info("Successful! Event DeleteNoVerifyUser created.");
    }
}
