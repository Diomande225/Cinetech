<?php
class ErrorHandler {
    public static function handleException($exception) {
        $error = [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine()
        ];

        if (ENV === 'development') {
            $error['trace'] = $exception->getTraceAsString();
        }

        self::logError($error);

        if (!headers_sent()) {
            header('HTTP/1.1 500 Internal Server Error');
        }

        if (ENV === 'development') {
            self::renderDevError($error);
        } else {
            self::renderProductionError();
        }

        exit;
    }

    public static function handleError($errno, $errstr, $errfile, $errline) {
        if (!(error_reporting() & $errno)) {
            return false;
        }

        $error = [
            'type' => $errno,
            'message' => $errstr,
            'file' => $errfile,
            'line' => $errline
        ];

        self::logError($error);

        if (ENV === 'development') {
            self::renderDevError($error);
        }

        return true;
    }

    private static function logError($error) {
        $logFile = __DIR__ . '/../logs/error.log';
        $timestamp = date('Y-m-d H:i:s');
        
        $logMessage = sprintf(
            "[%s] %s in %s:%s\n",
            $timestamp,
            $error['message'],
            $error['file'],
            $error['line']
        );

        if (isset($error['trace'])) {
            $logMessage .= "Stack trace:\n" . $error['trace'] . "\n";
        }

        error_log($logMessage, 3, $logFile);
    }

    private static function renderDevError($error) {
        include __DIR__ . '/../views/errors/dev_error.php';
    }

    private static function renderProductionError() {
        include __DIR__ . '/../views/errors/500.php';
    }
} 