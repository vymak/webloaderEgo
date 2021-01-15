<?php

declare(strict_types = 1);

namespace WebLoader\Nette;

use Nette\Http\IRequest;

/**
 * @author Jan Marek
 * @license MIT
 */
class CssUrlFilter extends \WebLoader\Filter\CssUrlsFilter
{
	public function __construct($docRoot, IRequest $httpRequest)
	{
		parent::__construct($docRoot, $httpRequest->getUrl()->getBasePath());
	}
}
