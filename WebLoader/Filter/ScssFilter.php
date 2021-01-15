<?php

declare(strict_types = 1);

namespace WebLoader\Filter;

use Leafo\ScssPhp\Compiler;

/**
 * Scss CSS filter
 *
 * @author Roman Matěna
 * @license MIT
 */
class ScssFilter
{

	/**
	 * @var \Leafo\ScssPhp\Compiler
	 */
	private $sc;


	public function __construct(?Compiler $sc = null)
	{
		$this->sc = $sc;
	}


	/**
	 * @return \Leafo\ScssPhp\Compiler|\scssc
	 */
	private function getScssC()
	{
		// lazy loading
		if (empty($this->sc)) {
			$this->sc = new Compiler();
		}

		return $this->sc;
	}


	/**
	 * Invoke filter
	 */
	public function __invoke(string $code, \WebLoader\Compiler $loader, string $file): string
	{
		if (pathinfo($file, PATHINFO_EXTENSION) === 'scss') {
			$this->getScssC()->setImportPaths(['', pathinfo($file, PATHINFO_DIRNAME) . '/']);
			return $this->getScssC()->compile($code);
		}

		return $code;
	}
}
