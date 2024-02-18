<?php

namespace Modestox\Ecommerce\Core\Console\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EcommerceMakeCommand extends Command
{
    protected $_files;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modestox-make:module {name}
                                                 {--pool=local : set pool code}
                                                 {--all : All items}
                                                 {--routers : Only routers}
                                                 {--model : Only model}
                                                 {--views : Only views}
                                                 {--migrations : Only migrations}
                                                 {--lang : Only lang}
                                                 ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command modestox make module';

    /**
     * Construct
     *
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->_files = $files;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('all')) {
            $this->input->setOption('routers', true);
            $this->input->setOption('model', true);
            $this->input->setOption('views', true);
            $this->input->setOption('migrations', true);
            $this->input->setOption('lang', true);
        }

        $this->setPool();

        /**
         * create Routers
         */
        if ($this->option('routers')) {
            $this->createRouters();
        }

        /**
         * create Model
         */
        if ($this->option('model')) {
            $this->createModel();
        }

        /**
         * create Views
         */
        if ($this->option('views')) {
            $this->createViews();
        }

        /**
         * create Migrations
         */
        if ($this->option('migrations')) {
            $this->createMigrations();
        }

        /**
         * create Lang
         */
        if ($this->option('lang')) {
            $this->createLang();
        }

        /**
         * create Controller
         */
        $this->createController();
    }

    /**
     * create Routers
     */
    private function createRouters(): void
    {
        try {

            $routerHtml = "<?php
/**
 * Modestox " . $this->argument("name") . "
 */";

            $path = "/Modestox/" . $this->option('pool') . "/" . $this->argument("name") . "/Routers/web.php";
            Storage::disk('modestox_path')->put($path, $routerHtml);

        } catch (\Exception $e) {
            $e->getMessage();
        }
    }

    /**
     * create Model
     */
    private function createModel(): void
    {
        try {

            $model = Str::singular(class_basename($this->argument('name')));
            $this->call('make:model', [
                'name' => "App\\Modestox\\" . $this->option('pool') . "\\" . $this->argument("name") . "\\Models\\" . $model
            ]);

        } catch (\Exception $e) {
            $e->getMessage();
        }
    }

    /**
     * create Views
     */
    private function createViews(): void
    {

    }

    /**
     * create Migrations
     */
    private function createMigrations(): void
    {
        try {

            $table = Str::plural(Str::snake(class_basename($this->argument('name'))));

            $path = "/app/Modestox/" . $this->option('pool') . "/" . $this->argument("name") . "/Migrations";
            $this->call('make:migration', [
                'name'     => "create_" . $table . "_table",
                '--create' => $table,
                '--path'   => $path
            ]);

        } catch (\Exception $e) {
            $e->getMessage();
        }
    }

    /**
     * create Lang
     */
    private function createLang(): void
    {
        try {

            $path = "/Modestox/" . $this->option('pool') . "/" . $this->argument("name") . "/Lang/en/locale.json";
            Storage::disk('modestox_path')->put($path, '{}');

        } catch (\Exception $e) {
            $e->getMessage();
        }
    }

    /**
     * create Controller
     */
    private function createController(): void
    {

    }

    /**
     * set Pool
     */
    private function setPool(): void
    {
        $pool = $this->option('pool');

        switch ($pool) {
            case 'core':
                $this->input->setOption('pool', 'Core');
                break;
            case 'admin':
                $this->input->setOption('pool', 'Adminhtml');
                break;
            case 'pub':
                $this->input->setOption('pool', 'Public');
                break;

            default:
                $this->input->setOption('pool', 'Local');
                break;
        }
    }
}
