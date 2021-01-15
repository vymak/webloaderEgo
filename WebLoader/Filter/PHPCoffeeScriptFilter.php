<?php

declare(strict_types = 1);

namespace WebLoader\Filter;

use CoffeeScript\Compiler;

/**
 * Coffee script filter implements with composer php compiler
 *
 * @author Jan Svantner
 */
class PHPCoffeeScriptFilter
{

	/**
	 * Invoke filter
	 */
	public function __invoke(string $code, \WebLoader\Compiler $loader, ?string $file = null): string
	{
		if (pathinfo($file, PATHINFO_EXTENSION) === 'coffee') {
			$code = $this->compileCoffee($code, $file);
		}

		return $code;
	}


	/**
	 * @param $source $string
	 * @param $file bool|NULL
	 * @throws \WebLoader\WebLoaderException
	 */
	public function compileCoffee($source, $file): string
	{
		try {
			return Compiler::compile($source, ['filename' => $file]);
		} catch (\Throwable $e) {
			throw new \WebLoader\WebLoaderException('CoffeeScript Filter Error: ' . $e->getMessage(), 0, $e);
		}
	}
}
