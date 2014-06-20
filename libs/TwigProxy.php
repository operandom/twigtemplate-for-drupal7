<?php

namespace libs;

class TwigProxy {
	
	public static $loader;
	public static $twig;
	
	public function __construct()
	{
		if (!self::$loader)
		{
			self::$loader = new \Twig_Loader_Filesystem(getcwd());
		}
		
		if (!self::$twig)
		{
			self::$twig = new \Twig_Environment(self::$loader, array(
				//@TODO Add cache system
				//'cache' => self::$cacheRoot,
			));
			
			self::$twig->addFunction(new \Twig_SimpleFunction('t', 't'));
			
			self::$twig->addFunction(new \Twig_SimpleFunction('render', function($value) {
				return render($value); // fix strict warning
			}));
			
			self::$twig->addFunction(new \Twig_SimpleFunction('theme', 'theme' ));
		}
	}
	
	public function render($view, $values)
	{
		return self::$twig->render($view, $values);
	}
}