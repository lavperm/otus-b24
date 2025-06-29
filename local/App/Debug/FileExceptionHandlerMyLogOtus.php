<?php
namespace App\Debug;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Diag\ExceptionHandlerFormatter;
use Bitrix\Main\Diag\FileExceptionHandlerLog;
use Bitrix\Main\Diag\FileLogger;
use Bitrix\Main\Config\Configuration;

class FileExceptionHandlerMyLogOtus extends FileExceptionHandlerLog
{
	private $level;
	const DEFAULT_PATH_FILE_LOG = 'Otus/logs/CustomExceptionLogsDefault.log';

	public function write($exception, $logType): void
	{
		//region

		$text = ExceptionHandlerFormatter::format($exception, false, $this->level);

		$context = [
			'type' => static::logTypeToString($logType),
		];

		$logLevel = static::logTypeToLevel($logType);

		$message = "[OTUS]  {date} - Host: {host} - {type} - {$text}\n";

		$this->logger->log($logLevel, $message, $context);

		//endregion

		//region Получаем из settings 'exception_handling' и проверяем на наличие 'file'
		//Если 'file' не заполненно используем путь для лог файла из DEFAULT_PATH_FILE_LOG
		//Если 'file' заполненн то  используем путь из settings

		/*$logFile = self:: DEFAULT_PATH_FILE_LOG;
		$exceptionHandlingConfig = Configuration::getValue('exception_handling');
		if (is_array($exceptionHandlingConfig))
		{
			$logSettings = $exceptionHandlingConfig['log']['settings'] ?? null;
			if (!empty($logSettings['file']))
			{
				$configuredPath = $logSettings['file'];
				$logFile = $configuredPath;
			}

			$log_text = sprintf("[%s] [%s] Ошибка: %s (%s)\n%s:%s",
				'OTUS',
				date('Y-m-d H:i:s'),
				$exception->getMessage(),
				$exception->getCode(),
				$exception->getFile(),
				$exception->getLine());

			$Separator = '-------Домашнее задание №2-2-------------';
			Debug::writeToFile($log_text, $Separator, $fileName = $logFile);
		}*/
			//endregion ----------------------------

	}

}

