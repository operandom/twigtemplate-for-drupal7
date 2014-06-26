<?php

namespace libs;

class TwigProxy {
	
	public static $loader;
	public static $twig;
	public static $cacheRoot;
	
	public function __construct()
	{
		if (!self::$loader)
		{
			self::$loader = new \Twig_Loader_Filesystem(getcwd());
		}
		
		if (!self::$cacheRoot)
		{
			self::$cacheRoot = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'cache';
			
			if (!file_exists(self::$cacheRoot))
			{
				mkdir(self::$cacheRoot);
			}
		}
		
		if (!self::$twig)
		{
			$twig = new \Twig_Environment(self::$loader, array(
			
				'autoescape' => false,
				//@TODO Add cache system in DB http://twig.sensiolabs.org/doc/recipes.html#using-a-database-to-store-templates
				//'cache' => self::$cacheRoot,
			));
			
			$twig->addFunction(new \Twig_SimpleFunction('t', 't'));
			
			$twig->addFunction(new \Twig_SimpleFunction('render', function ($element) {
				return render($element);
			}));
			
			$twig->addFunction(new \Twig_SimpleFunction('theme', 'theme' ));
			
			$twig->addFilter(new \Twig_SimpleFilter('repeat', 'str_repeat' ));
			
			$twig->addFilter(new \Twig_SimpleFilter('hide', function () {
				
				$arguments = func_get_args();
				$env = array_shift($arguments);
				$context = array_shift($arguments);
				$element = array_shift($arguments);
				
				foreach ($arguments as $key)
				{
					if (array_key_exists($key, $element))
					{
						$element[$key]['#printed'] = true;
					}
				}
				
				return $element;
			}, array('needs_context' => true, 'needs_environment' => true)));
			
			self::$twig = $twig;
		}
	}
	
	public function render($view, $values)
	{
		return self::$twig->render($view, $values);
	}
}