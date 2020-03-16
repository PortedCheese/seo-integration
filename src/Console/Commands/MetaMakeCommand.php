<?php

namespace PortedCheese\SeoIntegration\Console\Commands;

use App\Menu;
use App\MenuItem;
use PortedCheese\BaseSettings\Console\Commands\BaseConfigModelCommand;

class MetaMakeCommand extends BaseConfigModelCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:seo
                    {--all : Run all}
                    {--models : Export models}
                    {--policies : Export and create rules}
                    {--only-default : Create default rules}
                    {--controllers : Export controllers}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $packageName = "SeoIntegration";

    /**
     * The models that need to be exported.
     * @var array
     */
    protected $models = ['Meta'];

    protected $controllers = [
        "Admin" => ["MetaController"],
    ];

    protected $ruleRules = [
        [
            "title" => "Мета",
            "slug" => "meta",
            "policy" => "MetaPolicy",
        ],
    ];

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
     */
    public function handle()
    {
        $all = $this->option("all");

        if ($this->option("models") || $all) {
            $this->exportModels();
        }

        if ($this->option("controllers") || $all) {
            $this->exportControllers("Admin");
        }

        if ($this->option("policies") || $all) {
            $this->makeRules();
        }
    }
}
