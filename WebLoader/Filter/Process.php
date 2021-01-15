<?php

declare(strict_types = 1);

namespace WebLoader\Filter;

/**
 * Simple process wrapper
 *
 * @author Patrik Votoček
 * @license MIT
 */
class Process
{

	/**
	 * @param string|null $stdin
	 * @param string|null $cwd
	 * @param array|null $env
	 * @throws \RuntimeExeption
	 */
	public static function run(string $cmd, ?string $stdin = null, ?string $cwd = null, ?array $env = null): string
	{
		$descriptorspec = [
			0 => ['pipe', 'r'], // stdin
			1 => ['pipe', 'w'], // stdout
			2 => ['pipe', 'w'], // stderr
		];

		$pipes = [];
		$proc = proc_open($cmd, $descriptorspec, $pipes, $cwd, $env);

		if (!empty($stdin)) {
			fwrite($pipes[0], $stdin . PHP_EOL);
		}
		fclose($pipes[0]);

		$stdout = stream_get_contents($pipes[1]);
		$stderr = stream_get_contents($pipes[2]);

		$code = proc_close($proc);

		if ($code !== 0) {
			throw new \RuntimeException($stderr, $code);
		}

		return $stdout;
	}
}
