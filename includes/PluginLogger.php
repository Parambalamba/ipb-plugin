<?php

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';
use Psr\Log\AbstractLogger;

class PluginLogger extends AbstractLogger {

	public function log($level, $message, array $context = []): void
	{
		$dateFormatted = (new \DateTime())->format('Y-m-d H:i:s');

		$message = sprintf(
			'[%s] %s: %s%s',
			$dateFormatted,
			$level,
			$message,
			PHP_EOL
		);

		file_put_contents('plugin.log', $message, FILE_APPEND);

	}
}
