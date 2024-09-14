<?php
	namespace Exceptio\SonaliPayment;

	use Illuminate\Support\ServiceProvider;
	use Illuminate\Support\Facades\Gate;
	
	include_once(__DIR__.'/Helpers.php');

	class SonaliPaymentServiceProvider extends ServiceProvider
	{
		public function boot()
		{
			$this->loadRoutesFrom(__DIR__.'/routes/web.php');
			
			if(config('sonali-payment-config.do-migration')){
				$this->loadMigrationsFrom(__DIR__.'/Database/migrations');
			}

			$this->publishes([
				__DIR__.'/config/sonali-payment-config.php' => config_path('sonali-payment-config.php'),
			], 'config');

			$this->registerBladeDirectives();

			$this->registerGates();

		}

		public function register()
		{
			$this->mergeConfigFrom(
				__DIR__.'/config/sonali-payment-config.php', 'sonali-payment-config'
			);
		}

		/**
		 * Register Blade Directives.
		 *
		 * @return void
		 */
		protected function registerBladeDirectives()
		{
			$blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();
			
		}

		/**
		 * Register Gates.
		 *
		 * @return void
		 */
		protected function registerGates()
		{
			
		}

	}