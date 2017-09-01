<?php

namespace Brackets\Translatable\Test;

use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{

    /**
     * @var TestModel
     */
    protected $testModel;

    /**
     * @var TestRequest
     */
    protected $testRequest;

    /**
     * @var TestRequest
     */
    protected $testRequestWithRequiredLocales;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        $this->testModel = TestModel::first();
        $this->testRequest = new TestRequest;
        $this->testRequestWithRequiredLocales = new TestRequestWithRequiredLocales;

    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Brackets\Translatable\TranslatableServiceProvider::class
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

        $app['config']->set('translatable.locales', ['en', 'de', 'fr']);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->text('translatable_name');
            $table->string('regular_name');
        });

        TestModel::create([
            'translatable_name' => [
                'en' => 'EN Name',
                'de' => 'DE Name',
                'fr' => 'FR Name',
            ],
            'regular_name' => 'Regular Name'
        ]);
    }
}