<?php

declare(strict_types = 1);

namespace WebLoader\Nette;

use Nette\Utils\Html;

/**
 * Css loader
 *
 * @author Jan Marek
 * @license MIT
 */
class CssLoader extends \WebLoader\Nette\WebLoader
{

	/** @var string */
	private $media;

	/** @var string */
	private $title;

	/** @var string */
	private $type = 'text/css';

	/** @var bool */
	private $alternate = false;


	/**
	 * Get media
	 */
	public function getMedia(): string
	{
		return $this->media;
	}


	/**
	 * Get type
	 */
	public function getType(): string
	{
		return $this->type;
	}


	/**
	 * Get title
	 */
	public function getTitle(): string
	{
		return $this->title;
	}


	/**
	 * Is alternate ?
	 */
	public function isAlternate(): bool
	{
		return $this->alternate;
	}


	/**
	 * Set media
	 *
	 * @return \WebLoader\Nette\CssLoader
	 */
	public function setMedia(string $media): self
	{
		$this->media = $media;
		return $this;
	}


	/**
	 * Set type
	 *
	 * @return \WebLoader\Nette\CssLoader
	 */
	public function setType(string $type): self
	{
		$this->type = $type;
		return $this;
	}


	/**
	 * Set title
	 *
	 * @return \WebLoader\Nette\CssLoader
	 */
	public function setTitle(string $title): self
	{
		$this->title = $title;
		return $this;
	}


	/**
	 * Set alternate
	 *
	 * @return \WebLoader\Nette\CssLoader
	 */
	public function setAlternate(bool $alternate): self
	{
		$this->alternate = $alternate;
		return $this;
	}


	/**
	 * Get link element
	 */
	public function getElement(string $source): Html
	{
		if ($this->alternate) {
			$alternate = ' alternate';
		} else {
			$alternate = '';
		}

		return Html::el('link')->rel('stylesheet' . $alternate)->type($this->type)->media($this->media)->title($this->title)->href($source);
	}
}
