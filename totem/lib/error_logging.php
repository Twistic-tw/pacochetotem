<?php

// Habilitar reporte de todos los errores y warnings
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Asegurar logging a fichero
ini_set('log_errors', '1');

// Ruta absoluta del fichero de log
$logDirectory = __DIR__ . '/../logs';
if (!is_dir($logDirectory)) {
    @mkdir($logDirectory, 0775, true);
}
$logFile = $logDirectory . '/php_errors.log';
ini_set('error_log', $logFile);

// Registrar errores fatales al finalizar
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null) {
        $message = sprintf('[FATAL] %s in %s on line %d', $error['message'] ?? '', $error['file'] ?? '', $error['line'] ?? 0);
        error_log($message);
    }
});

// Handler para warnings/notices no capturados (opcional)
set_error_handler(function ($severity, $message, $file, $line) {
    // Respetar los niveles silenciados con @
    if (!(error_reporting() & $severity)) {
        return false;
    }
    $levelNames = array(
        E_ERROR => 'E_ERROR',
        E_WARNING => 'E_WARNING',
        E_PARSE => 'E_PARSE',
        E_NOTICE => 'E_NOTICE',
        E_CORE_ERROR => 'E_CORE_ERROR',
        E_CORE_WARNING => 'E_CORE_WARNING',
        E_COMPILE_ERROR => 'E_COMPILE_ERROR',
        E_COMPILE_WARNING => 'E_COMPILE_WARNING',
        E_USER_ERROR => 'E_USER_ERROR',
        E_USER_WARNING => 'E_USER_WARNING',
        E_USER_NOTICE => 'E_USER_NOTICE',
        E_STRICT => 'E_STRICT',
        E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
        E_DEPRECATED => 'E_DEPRECATED',
        E_USER_DEPRECATED => 'E_USER_DEPRECATED',
    );
    $level = isset($levelNames[$severity]) ? $levelNames[$severity] : 'E_UNKNOWN';
    error_log(sprintf('[%s] %s in %s on line %d', $level, $message, $file, $line));
    return false; // permitir que PHP continúe con su manejo por defecto
});

?>


