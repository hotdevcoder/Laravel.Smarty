<?php
namespace Ytake\LaravelSmarty\Console;

use Smarty;
use Illuminate\Console\Command;
use Illuminate\Config\Repository;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class CompiledCommand
 * @package Ytake\LaravelSmarty\Console
 * @author yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class CompiledCommand extends Command
{

    /** @var Smarty */
    protected $smarty;

    /** @var Repository  */
    protected $config;

    /**
     * @param Smarty $smarty
     * @param Repository $config
     */
    public function __construct(Smarty $smarty, Repository $config)
    {
        parent::__construct();
        $this->smarty = $smarty;
        $this->config = $config;
    }

	/**
	 * The console command name.
	 * @var string
	 */
	protected $name = 'ytake:smarty-optimize';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'compiles all known templates';

	/**
	 * Execute the console command.
	 * @return void
	 */
	public function fire()
	{
        $configureFileExtension = $this->config->get('laravel-smarty::extension', 'tpl');
        $fileExtension = (is_null($this->option('extension'))) ? $configureFileExtension : $this->option('extension');
        ob_start();
        $compileFiles = $this->smarty->compileAllTemplates($fileExtension, $this->option('force'));
        $contents = ob_get_contents();
        ob_get_clean();
        $this->info("{$compileFiles} template files recompiled");
        $this->comment(str_replace("<br>", "\n", trim($contents)));
        return;
	}

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('extension', 'e', InputOption::VALUE_OPTIONAL, 'specified smarty file extension'),
            array('force', null, InputOption::VALUE_NONE, 'compiles template files found in views directory'),
        );
    }
}