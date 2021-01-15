<?php

declare(strict_types = 1);

namespace WebLoader\Nette;

use Nette\Utils\Html;
use WebLoader\Compiler;
use WebLoader\FileCollection;

/**
 * Web loader
 *
 * @author Jan Marek
 * @license MIT
 */
abstract class WebLoader extends \Nette\Application\UI\Control
{

	/** @var \WebLoader\Compiler */
	private $compiler;

	/** @var string */
	private $tempPath;

	/** @var bool */
	private $appendLastModified;


	public function __construct(Compiler $compiler, $tempPath, $appendLastModified)
	{
		parent::__construct();
		$this->compiler = $compiler;
		$this->tempPath = $tempPath;
		$this->appendLastModified = $appendLastModified;
	}


	public function getCompiler(): Compiler
	{
		return $this->compiler;
	}


	public function setCompiler(Compiler $compiler): void
	{
		$this->compiler = $compiler;
	}


	public function getTempPath(): string
	{
		return $this->tempPath;
	}


	public function setTempPath(string $tempPath): void
	{
		$this->tempPath = $tempPath;
	}


	/**
	 * Get html element including generated content
	 */
	abstract public function getElement(string $source): Html;


	/**
	 * Generate compiled file(s) and render link(s)
	 */
	public function render(): void
	{
		$hasArgs = func_num_args() > 0;

		if ($hasArgs) {
			$backup = $this->compiler->getFileCollection();
			$newFiles = new FileCollection($backup->getRoot());
			$newFiles->addFiles(func_get_args());
			$this->compiler->setFileCollection($newFiles);
		}

		// remote files
		foreach ($this->compiler->getFileCollection()->getRemoteFiles() as $file) {
			echo $this->getElement($file), PHP_EOL;
		}

		foreach ($this->compiler->generate() as $file) {
			echo $this->getElement($this->getGeneratedFilePath($file)), PHP_EOL;
		}

		if ($hasArgs) {
			$this->compiler->setFileCollection($backup);
		}
	}


	protected function getGeneratedFilePath($file)
	{
		$path = $this->tempPath . '/' . $file->file;

		if ($this->appendLastModified) {
			$path .= '?' . $file->lastModified;
		}

		return $path;
	}
}
