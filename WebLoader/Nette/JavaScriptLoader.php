<?php

declare(strict_types = 1);

namespace WebLoader\Nette;

use Nette\Utils\Html;

/**
 * JavaScript loader
 *
 * @author Jan Marek
 * @license MIT
 */
class JavaScriptLoader extends \WebLoader\Nette\WebLoader
{

	/**
	 * Get script element
	 */
	public function getElement(string $source): Html
	{
		$el = Html::el('script');
		$this->getCompiler()->isAsync() ? $el = $el->addAttributes(['async' => true]) : null;
		$this->getCompiler()->isDefer() ? $el = $el->addAttributes(['defer' => true]) : null;
		($nonce = $this->getCompiler()->getNonce()) ? $el = $el->addAttributes(['nonce' => $nonce]) : null;

		return $el->src($source);
	}
}
