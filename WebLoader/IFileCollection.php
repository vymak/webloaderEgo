<?php

declare(strict_types = 1);

namespace WebLoader;

/**
 * @author Jan Marek
 */
interface IFileCollection
{
	public function getRoot(): string;

	/**
	 * @return array
	 */
	public function getFiles(): array;

	/**
	 * @return array
	 */
	public function getRemoteFiles(): array;

	/**
	 * @return array
	 */
	public function getWatchFiles(): array;
}
