<?php

namespace EvolutionCMS\Dmi3yy\Pwa\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PwaCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'pwa:install';

    /**
     * @var string
     */
    protected $description = 'Configure after install/update';

    /**
     * @var
     */
    protected $evo;

    /**
     * @var string
     */
    public $directory = EVO_CORE_PATH . 'custom/config/cms/settings/';

    /**
     * @var string
     */
    public $fileName = 'pwa.php';

    /**
     * @var string
     */
    public $directoryBladeDirective = EVO_CORE_PATH . 'custom/config/view/directive/';

    /**
     * @var string
     */
    public $fileNameBladeDirective = 'evopwa.php';

    /**
     * SeriousCustomTemplateCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->evo = EvolutionCMS();
        $this->fileName = $this->directory . $this->fileName;

        $this->fileNameBladeDirective = $this->directoryBladeDirective . $this->fileNameBladeDirective;
    }

    /**
     *
     */
    public function handle()
    {
        if (File::isFile($this->fileName)) {
            $name = $this->askRewrite();
        } else {
            $name = 'y';
        }
        if (strtolower($name) == 'y') {
            $pwa_name = $this->ask('Please enter PWA name? (Like: Site_Name)');
            $short_name = $this->ask('Please enter Short PWA name? (Like: Site_Name)');
            if (!File::isDirectory($this->directory)) {
                File::makeDirectory($this->directory, 0755, true);
            }
            File::put($this->fileName, "<?php return [
                'name' => '".$pwa_name."',
                'short_name' => '".$short_name."',
                'apple-touch-icon' => '/assets/images/evo-logo.png',
                'theme_color' => '#000000',
                'background_color' => '#ffffff',
                'display' => 'standalone',
                'scope' => '/',
                'start_url' => '/',
                'icons' => [
                    [
                        'src'=> '/assets/images/evo-logo.png',
                        'sizes'=>'192x192'
                    ],
                    [
                        'src'=> '/assets/images/evo-logo.png',
                        'sizes'=>'512x512'
                    ]
                ] 
            ];");

            if (!File::isDirectory($this->directoryBladeDirective)) {
                File::makeDirectory($this->directoryBladeDirective, 0755, true);
            }
            File::put($this->fileNameBladeDirective, '<?php return [EvolutionCMS\Dmi3yy\Pwa\Controllers\PwaController::class, "evopwa"];');
        }
    }

    /**
     * @return mixed
     */
    public function askRewrite()
    {
        $answer = $this->ask('Config PWA already exist, do you wish rewrite? (Y/N)');
        if (strtolower($answer) != 'y' && strtolower($answer) != 'n') {
            return $this->askRewrite();
        } else {
            return $answer;
        }
    }

}