<?php

declare(strict_types = 1);

namespace WebLoader\Filter;

use WebLoader\Compiler;
use WebLoader\Path;

/**
 * Absolutize urls in CSS
 *
 * @author Jan Marek
 * @license MIT
 */
class CssUrlsFilter
{

	/**
	 * @var string
	 */
	protected $basePath;

	/**
	 * @var string
	 */
	private $docRoot;


	/**
	 * @param string $docRoot web document root
	 * @throws \WebLoader\InvalidArgumentException
	 */
	public function __construct(string $docRoot, string $basePath = '/')
	{
		$this->docRoot = Path::normalize($docRoot);

		if (!is_dir($this->docRoot)) {
			throw new \WebLoader\InvalidArgumentException('Given document root is not directory.');
		}

		$this->basePath = $basePath;
	}


	public function setBasePath(string $basePath): void
	{
		$this->basePath = $basePath;
	}


	/**
	 * Make relative url absolute
	 *
	 * @param string $url image url
	 * @param string $quote single or double quote
	 * @param string $cssFile absolute css file path
	 */
	public function absolutizeUrl(string $url, string $quote, string $cssFile): string
	{
		// is already absolute
		if (preg_match('/^([a-z]+:\/)?\//', $url)) {
			return $url;
		}

		$cssFile = Path::normalize($cssFile);

		// inside document root
		if (strncmp($cssFile, $this->docRoot, strlen($this->docRoot)) === 0) {
			$path = $this->basePath . substr(dirname($cssFile), strlen($this->docRoot)) . DIRECTORY_SEPARATOR . $url;
		} else {
			// outside document root we don't know
			return $url;
		}

		$path = $this->cannonicalizePath($path);

		return $quote === '"' ? addslashes($path) : $path;
	}


	/**
	 * Cannonicalize path
	 *
	 * @return string path
	 */
	public function cannonicalizePath(string $path): string
	{
		$path = strtr($path, DIRECTORY_SEPARATOR, '/');

		$pathArr = [];
		foreach (explode('/', $path) as $i => $name) {
			if ($name === '.' || ($name === '' && $i > 0)) {
				continue;
			}

			if ($name === '..') {
				array_pop($pathArr);
				continue;
			}

			$pathArr[] = $name;
		}

		return implode('/', $pathArr);
	}


	/**
	 * Invoke filter
	 */
	public function __invoke(string $code, Compiler $loader, ?string $file = null): string
	{
		// thanks to kravco
		$regexp = '~
			(?<![a-z])
			url\(                                     ## url(
				\s*                                   ##   optional whitespace
				([\'"])?                              ##   optional single/double quote
				(?!data:)                             ##   keep data URIs
				(   (?: (?:\\\\.)+                    ##     escape sequences
					|   [^\'"\\\\,()\s]+              ##     safe characters
					|   (?(1)   (?!\1)[\'"\\\\,() \t] ##       allowed special characters
						|       ^                     ##       (none, if not quoted)
						)
					)*                                ##     (greedy match)
				)
				(?(1)\1)                              ##   optional single/double quote
				\s*                                   ##   optional whitespace
			\)                                        ## )
		~xs';

		$self = $this;

		return preg_replace_callback($regexp, function ($matches) use ($self, $file) {
			return "url('" . $self->absolutizeUrl($matches[2], $matches[1], $file) . "')";
		}, $code);
	}
}
