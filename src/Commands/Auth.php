<?php

namespace KdymSolucoes\LaravelBoilerplate\Commands;

use Illuminate\Console\Command;

class Auth extends Command
{
    const ROOT_PATH = __DIR__ . '/../../../../../';
    const SOURCES_PATH = __DIR__ . '/../';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'boiler:auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        echo "Criando autenticação... ";

        $this->makeAuth();

        $this->setTemplates();

        $this->setControllers();

        $this->setRoutes();

        echo "\033[32mPronto\033[0m\n";

        echo "\033[32mAutenticação configurada com sucesso\033[0m\n";
    }

    public function makeAuth() {
        $command = 'php artisan make:auth --force';

        //echo "$command \n";

        exec($command);
    }

    public function setTemplates() {
        copy(self::SOURCES_PATH . '/resources/views/auth/login.blade.php', self::ROOT_PATH . '/resources/views/auth/login.blade.php');
    }

    public function setControllers() {
        copy(self::SOURCES_PATH . '/Controllers/UsersController.php', self::ROOT_PATH . '/app/Http/Controllers/UsersController.php');
    }

    public function setRoutes() {
        $routesFile = self::ROOT_PATH . '/routes/web.php';
        $command = '\KdymSolucoes\LaravelBoilerplate\LaravelBoilerplateServiceProvider::routes();';

        $file = fopen($routesFile, 'r');

        $addLine = true;
        while (($line = fgets($file)) !== false) {
            if ($line == $command) {
                $addLine = false;
            }
        }

        fclose($file);

        if ($addLine) {
            $file = fopen($routesFile, 'a');
            fwrite($file, "\n\n" . $command);
            fclose($file);
        }
    }
}
