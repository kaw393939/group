<?php

namespace Kaw393939\Group\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Database\Schema\SQLiteBuilder;
use Illuminate\Support\Fluent;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Connection;
use Kaw393939\Group\Models\Group;
use Kaw393939\Group\Models\Role;

abstract class TestCase extends Orchestra
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Kaw393939\Group\GroupServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application   $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
        //setup db config if needed
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            //use this for automated testing
            //'database' => ':memory:',
            //use this for local development so you can see the database
            'database' => 'C:\Users\Monimus\PhpstormProjects\packages\Group\database\database.sqlite',
            'prefix' => '',
        ]);

        $app['config']->set('app.debug', env('APP_DEBUG', true));


    }

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->hotfixSqlite();
    }

    /**
     *
     */
    public function hotfixSqlite()
    {
        Connection::resolverFor('sqlite', function ($connection, $database, $prefix, $config) {
            return new class($connection, $database, $prefix, $config) extends SQLiteConnection {
                public function getSchemaBuilder()
                {
                    if ($this->schemaGrammar === null) {
                        $this->useDefaultSchemaGrammar();
                    }
                    return new class($this) extends SQLiteBuilder {
                        protected function createBlueprint($table, \Closure $callback = null)
                        {
                            return new class($table, $callback) extends Blueprint {
                                public function dropForeign($index)
                                {
                                    return new Fluent();
                                }
                            };
                        }
                    };
                }
            };
        });
    }

    public function setUp()
    {
        parent::setUp();
        //I can load any local factories if I want to
        $this->withFactories(__DIR__ . '/../database/factories');

        //make the base path under tests folder
        $this->app->setBasePath(__DIR__ . '/../');

        //Because I use dynamic facades
        \File::makeDirectory(base_path("storage/framework/cache"), 0755, true, true);

        //I can deliver routes for testing
        //$this->app['router']->get('example', function () {
        //     return view("testing");
        //})->name('featured');

        //Load a view for testing
        //\View::addLocation(__DIR__ . '/../views');

        $this->loadLaravelMigrations(['--database' => 'testbench']);

        //Any migrations I need to bring in

        //dd(realpath(__DIR__ . '/../database/migrations'));
        /*
        $this->loadMigrationsFrom([
            '--database' => 'testbench',
            '--path' => realpath(__DIR__ . '/../database/migrations')
        ]);
        */
        $this->artisan('migrate:refresh', ['--database' => 'testbench']);

        $user = factory(\Kaw393939\Group\Models\User::class)->create();
        $ownerRole = Role::where('name', 'owner')->first();
        $group = Group::find(1);
        $user->attachRole($ownerRole, $group);
    }


}
