<?php namespace furkankadioglu\LaravelModuleManagement;

/************************
*
*	Rys - Furkan Kadıoğlu
*	February - 2016	
*	http://github.com/furkankadioglu
*
*************************/

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider {

	protected $files;

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {


		// php artisan vendor:publish
		$this->publishes([__DIR__.'/Config/modulemanagement.php' => config_path('modulemanagement.php'),
		], 'config');

		$this->publishes([
			__DIR__.'/App/Http/Controllers/AdminTemplateController.php' => app_path('/Http/Controllers/AdminTemplateController.php'),
			__DIR__.'/App/Http/Controllers/AdminController.php' => app_path('/Http/Controllers/AdminController.php'),
			__DIR__.'/App/Http/Controllers/MainController.php' => app_path('/Http/Controllers/MainController.php'),
			__DIR__.'/App/Http/Controllers/MainTemplateController.php' => app_path('/Http/Controllers/MainTemplateController.php'),
			__DIR__.'/App/BaseHelpers.php' => app_path('BaseHelpers.php'),

		], 'app');


		if(is_dir(app_path().'/Modules/')) {

			$modules = config("modulemanagement.enabledModules") ?: array_map('class_basename', $this->files->directories(app_path().'/Modules/'));
			foreach($modules as $module)  {
				
				$routes = app_path().'/Modules/'.$module.'/routes.php';
				$helper = app_path().'/Modules/'.$module.'/helper.php';
				$views  = app_path().'/Modules/'.$module.'/Views';
				$trans  = app_path().'/Modules/'.$module.'/Translations';

				if($this->files->exists($routes)) include $routes;
				if($this->files->exists($helper)) include $helper;
				if($this->files->isDirectory($views)) $this->loadViewsFrom($views, $module);
				if($this->files->isDirectory($trans)) $this->loadTranslationsFrom($trans, $module);
			}
		}

	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register() {

		$this->files = new Filesystem;
		$this->registerMakeCommand();
	}

	/**
	 * Register the "make:module" console command.
	 *
	 * @return Console\ModuleMakeCommand
	 */
	protected function registerMakeCommand() {

		$this->commands('modules.make');
		
		$bind_method = method_exists($this->app, 'bindShared') ? 'bindShared' : 'singleton';

		$this->app->{$bind_method}('modules.make', function($app) {
			return new Console\ModuleMakeCommand($this->files);
		});
	}

}
