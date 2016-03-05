<?php namespace furkankadioglu\LaravelModuleManagement\Console;

/************************
*
*	Rys - Furkan Kadıoğlu
*	February - 2016	
*	http://github.com/furkankadioglu
*
*************************/

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleMakeCommand extends GeneratorCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'make:module';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new module (folder structure)';


	/**
	 * The type of class being generated.
	 *
	 * @var string
	 */
	protected $type = 'Module';

	/**
	 * The current stub.
	 *
	 * @var string
	 */
	protected $currentStub;
	protected $stubName;

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		// check if module exists
		if($this->files->exists(app_path().'/Modules/'.$this->getNameInput())) 
			return $this->error($this->type.' already exists!');

		// Create Controller
		$this->generate('controller');

		// Create Model
		$this->generate('model');

		// Create Views folder
		$this->generate('view');
		
		//Flag for no translation
		if ( ! $this->option('no-translation')) // Create Translations folder
			$this->generate('translation');

		// Create Routes file
		$this->generate('routes');
		
		// Create Helper file
		$this->generate('helper');

		// Create Details file
		$this->generate('details');





		if ( ! $this->option('no-migration'))
		{
			$table = str_plural(snake_case(class_basename($this->argument('name'))));
			$this->call('make:migration', ['name' => "create_{$table}_table", '--create' => $table]);
		}

		$this->info($this->type.' created successfully.');
	}


	protected function generate($type) {

		switch ($type) {
			case 'controller':
				$filename = studly_case(class_basename($this->getNameInput()).ucfirst($type));
				break;

			case 'model':
				$filename = studly_case(class_basename($this->getNameInput()));
				break;

			case 'view':
				$filename = 'index.blade';
				break;
				
			case 'translation':
				$filename = 'general';
				break;
			
			case 'routes':
				$filename = 'routes';
				break;
				
			case 'helper':
				$filename = 'helper';
				break;

			case 'details':
				$filename = 'details';
				break;
		}


		if ($type != 'routes' && $type != 'helper' && $type != 'details') 
		{

			// Folder Variables
			$folder = ucfirst($type).'s\\';
			$folder2 = ucfirst($type).'s\\';
			$folder3 = ucfirst($type).'s\\';


			if ($type === 'translation') 
			{
				// English Folder
				$folder2 .= 'en\\';
				$name = $this->parseName('Modules\\'.studly_case(ucfirst($this->getNameInput())).'\\'.$folder2.$filename);
				if ($this->files->exists($path = $this->getPath($name))) 
					return $this->error($this->type.' already exists!');

				$this->currentStub = __DIR__.'/stubs/'.$type.'.stub';
				$this->stubName = $type.'.stub';

				$this->makeDirectory($path);
				$this->files->put($path, $this->buildClass($name));

				// Turkish Folder
				$folder .= 'tr\\';
			}
			elseif($type == 'view')
			{
				// API Folder
				$folder3 .= 'api\\';
				$name = $this->parseName('Modules\\'.studly_case(ucfirst($this->getNameInput())).'\\'.$folder3.$filename);
				if ($this->files->exists($path = $this->getPath($name))) 
					return $this->error($this->type.' already exists!');

				$this->currentStub = __DIR__.'/stubs/'.$type.'.stub';
				$this->stubName = $type.'.stub';
				$this->makeDirectory($path);
				$this->files->put($path, $this->buildClass($name));


				$name = $this->parseName('Modules\\'.studly_case(ucfirst($this->getNameInput())).'\\'.$folder.$filename);
				if ($this->files->exists($path = $this->getPath($name))) 
					return $this->error($this->type.' already exists!');

				$this->currentStub = __DIR__.'/stubs/'.$type.'.stub';
				$this->stubName = $type.'.stub';
				$this->makeDirectory($path);
				$this->files->put($path, $this->buildClass($name));

				// ADMIN Folder
				$folder .= 'admin\\';
			}
			else
			{
				$folder .= '';
			}
		}
		else
		{
			$folder = '';
		}
		

		$name = $this->parseName('Modules\\'.studly_case(ucfirst($this->getNameInput())).'\\'.$folder.$filename);
		if ($this->files->exists($path = $this->getPath($name))) 
			return $this->error($this->type.' already exists!');

		$this->currentStub = __DIR__.'/stubs/'.$type.'.stub';
		$this->stubName = $type.'.stub';

		$this->makeDirectory($path);
		$this->files->put($path, $this->buildClass($name));

		if($type == 'controller')
		{
			// Api Controller Generate
			
			$filename = studly_case(class_basename($this->getNameInput())."Api".ucfirst($type));
			$name = $this->parseName('Modules\\'.studly_case(ucfirst($this->getNameInput())).'\\'.$folder.$filename);
			if ($this->files->exists($path = $this->getPath($name))) 
				return $this->error($this->type.' already exists!');

			$this->currentStub = __DIR__.'/stubs/'.$type.'-api.stub';
			$this->stubName = $type.'-api.stub';
			$this->makeDirectory($path);
			$this->files->put($path, $this->buildClass($name));

			// Admin Controller Generate

			$filename = studly_case(class_basename($this->getNameInput())."Admin".ucfirst($type));
			$name = $this->parseName('Modules\\'.studly_case(ucfirst($this->getNameInput())).'\\'.$folder.$filename);
			if ($this->files->exists($path = $this->getPath($name))) 
				return $this->error($this->type.' already exists!');

			$this->currentStub = __DIR__.'/stubs/'.$type.'-admin.stub';
			$this->stubName = $type.'-admin.stub';

			$this->makeDirectory($path);
			$this->files->put($path, $this->buildClass($name));
		}
	}

	/**
	 * Get the full namespace name for a given class.
	 *
	 * @param  string  $name
	 * @return string
	 */
	protected function getNamespace($name)
	{
		return trim(implode('\\', array_map('ucfirst', array_slice(explode('\\', studly_case($name)), 0, -1))), '\\');
	}

	/**
	 * Build the class with the given name.
	 *
	 * @param  string  $name
	 * @return string
	 */
	protected function buildClass($name)
	{
		$stub = $this->files->get($this->getStub());

		//  Change stub file and replace functions
		if($this->stubName == "controller-admin.stub")
		{
			return $this->replaceName($stub, $this->getNameInput())->replaceNamespace($stub, $name)->replaceAdminClass($stub, $name);
		}
		elseif($this->stubName == "controller-api.stub")
		{
			return $this->replaceName($stub, $this->getNameInput())->replaceNamespace($stub, $name)->replaceApiClass($stub, $name);
		}
		else
		{
			return $this->replaceName($stub, $this->getNameInput())->replaceNamespace($stub, $name)->replaceClass($stub, $name);
		}
		
	}

	/**
	 * Replace the name for the given stub.
	 *
	 * @param  string  $stub
	 * @param  string  $name
	 * @return string
	 */
	protected function replaceName(&$stub, $name)
	{
		$stub = str_replace('DummyTitle', $name, $stub);
		$stub = str_replace('DummyUCtitle', ucfirst(studly_case($name)), $stub);
		return $this;
	}

	/**
	 * Replace the class name for the given stub.
	 *
	 * @param  string  $stub
	 * @param  string  $name
	 * @return string
	 */
	protected function replaceClass($stub, $name)
	{
		$class = class_basename($name);
		return str_replace('DummyClass', $class, $stub);
	}

	/**
	 * Replace the class name for the given stub.
	 *
	 * @param  string  $stub
	 * @param  string  $name
	 * @return string
	 */
	protected function replaceAdminClass($stub, $name)
	{
		$class = class_basename($name);
		return str_replace('DummyAdminClass', $class, $stub);
	}

	/**
	 * Replace the class name for the given stub.
	 *
	 * @param  string  $stub
	 * @param  string  $name
	 * @return string
	 */
	protected function replaceApiClass($stub, $name)
	{
		$class = class_basename($name);
		return str_replace('DummyApiClass', $class, $stub);
	}


	/**
	 * Get the stub file for the generator.
	 *
	 * @return string
	 */
	protected function getStub()
	{
		return $this->currentStub;
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			['name', InputArgument::REQUIRED, 'Module name.'],
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			['no-migration', null, InputOption::VALUE_NONE, 'Do not create new migration files.'],
			['no-translation', null, InputOption::VALUE_NONE, 'Do not create module translation filesystem.'],
		);
	}

}
