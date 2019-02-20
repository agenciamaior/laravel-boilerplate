<?php

namespace KdymSolucoes\LaravelBoilerplate\Commands;

use Illuminate\Console\Command;
use File;

class Install extends Command
{
    const ROOT_PATH = __DIR__ . '/../../../../../';
    const SOURCES_PATH = __DIR__ . '/../';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'boiler:install';

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
        echo "Instalando Boilerplate...\n";

        echo "Instalando pacotes NPM...\n";

        $this->installNpmPackages();

        echo "\033[32mPacotes NPM instalados\033[0m\n";

        $this->makeAuth();

        echo "Copiando arquivos... ";

        $this->copyMigrations();

        $this->setModels();

        $this->setControllers();

        $this->setACL();

        //$this->setRoutes();

        $this->setStyles();

        $this->setScripts();

        $this->setTemplates();

        $this->copyImages();

        echo "\033[32mPronto\033[0m\n";

        echo "\033[32mBoilerplate instalado com sucesso\033[0m\n";
    }

    public function installNpmPackages() {
        $command = 'npm install';

        //echo "$command \n";

        exec($command);        

        $npmLibraries = [
            'admin-lte@v3.0.0-alpha.2',
            '@fortawesome/fontawesome-free',
            'jquery-validation',
            'datatables.net-bs4',
            'select2',
            'jquery-maskmoney',
            'jquery-mask-plugin',
            'moment',
            'numeral',
            'webpack-jquery-ui',
        ];

        $command = 'npm i ' . implode(' ', $npmLibraries);

        //echo "$command \n";

        exec($command);
    }

    public function makeAuth() {
        $command = 'php artisan make:auth --force';

        echo "$command \n";

        exec($command);
    }

    public function copyMigrations() {
        File::copyDirectory(self::SOURCES_PATH . '/migrations', self::ROOT_PATH . '/database/migrations');
    }

    public function setACL() {
        File::copyDirectory(self::SOURCES_PATH . '/Providers', self::ROOT_PATH . '/app/Providers');
        File::copyDirectory(self::SOURCES_PATH . '/Policies', self::ROOT_PATH . '/app/Policies');
    }

    public function setStyles() {
        // copy(self::SOURCES_PATH . '/resources/sass/_boilerplate.scss', self::ROOT_PATH . '/resources/sass/_boilerplate.scss');
        File::copyDirectory(self::SOURCES_PATH . '/resources/sass', self::ROOT_PATH . '/resources/sass');
    }

    public function setScripts() {
        // copy(self::SOURCES_PATH . '/resources/js/boilerplate.js', self::ROOT_PATH . '/resources/js/boilerplate.js');

        // $i18nDir = self::ROOT_PATH . '/resources/js/i18n';

        // if (!is_dir($i18nDir)) {
        //     mkdir($i18nDir);
        // }

        // $i18nBoilerplateDir = $i18nDir . '/boilerplate';

        // if (!is_dir($i18nBoilerplateDir)) {
        //     mkdir($i18nBoilerplateDir);
        // }

        // copy(self::SOURCES_PATH . '/resources/js/i18n/boilerplate/pt-br.js', self::ROOT_PATH . '/resources/js/i18n/boilerplate/pt-br.js');

        File::copyDirectory(self::SOURCES_PATH . '/resources/js', self::ROOT_PATH . '/resources/js');

        $bootstrapJsFile = self::ROOT_PATH . '/resources/js/bootstrap.js';
        $requireCommand = "require('./boilerplate');";

        $file = fopen($bootstrapJsFile, 'r');

        $addLine = true;
        while (($line = fgets($file)) !== false) {
            if ($line == $requireCommand) {
                $addLine = false;
            }
        }

        fclose($file);

        if ($addLine) {
            $file = fopen($bootstrapJsFile, 'a');
            fwrite($file, "\n\n" . $requireCommand);
            fclose($file);
        }
    }

    public function setModels() {
        File::copyDirectory(self::SOURCES_PATH . '/models', self::ROOT_PATH . '/app');
    }

    public function setControllers() {
        // copy(self::SOURCES_PATH . '/Controllers/UsersController.php', self::ROOT_PATH . '/app/Http/Controllers/UsersController.php');

        File::copyDirectory(self::SOURCES_PATH . '/Controllers', self::ROOT_PATH . '/app/Http/Controllers');
    }

    public function setTemplates() {
        // $viewsDir = self::ROOT_PATH . '/resources/views/boilerplate';

        // if (!is_dir($viewsDir)) {
        //     mkdir($viewsDir);
        // }

        // copy(self::SOURCES_PATH . '/resources/views/auth/login.blade.php', self::ROOT_PATH . '/resources/views/auth/login.blade.php');

        // copy(self::SOURCES_PATH . '/resources/views/boilerplate/main-menu.blade.php', self::ROOT_PATH . '/resources/views/boilerplate/main-menu.blade.php');
        // copy(self::SOURCES_PATH . '/resources/views/boilerplate/master.blade.php', self::ROOT_PATH . '/resources/views/boilerplate/master.blade.php');
        // copy(self::SOURCES_PATH . '/resources/views/boilerplate/page.blade.php', self::ROOT_PATH . '/resources/views/boilerplate/page.blade.php');
        // copy(self::SOURCES_PATH . '/resources/views/boilerplate/raw.blade.php', self::ROOT_PATH . '/resources/views/boilerplate/raw.blade.php');

        File::copyDirectory(self::SOURCES_PATH . '/resources/views', self::ROOT_PATH . '/resources/views');
    }

    public function copyImages() {
        // $imagesDir = self::ROOT_PATH . '/public/images';

        // if (!is_dir($imagesDir)) {
        //     mkdir($imagesDir);
        // }

        // copy(self::SOURCES_PATH . '/public/images/user.png', self::ROOT_PATH . '/public/images/user.png');

        File::copyDirectory(self::SOURCES_PATH . '/public/images', self::ROOT_PATH . '/public/images');
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
