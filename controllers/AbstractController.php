<?php

declare(strict_types=1);

namespace FwTest\Controller;

use FwTest\Core\Database;
use FwTest\Core\Session;

class AbstractController
{
    private ?Database $db = null;
    public array $array_constant;

    public function __construct()
    {
        $this->array_constant = $this->getConstant();
    }

    /**
     * Retourne la connexion à la base de données
     */
    public function getDatabaseConnection(): Database
    {
        if (!$this->db) {
            $this->db = new Database();
        }
        return $this->db;
    }

    /**
     * Renders a template file with optional variables
     */
    public function render(string $filePath, array $arrayArgs = []): string
    {
        $templateDir = realpath(__DIR__ . '/../templates');
        if (!$templateDir) {
            throw new \RuntimeException('Templates directory not found');
        }

        $fullPath = $templateDir . '/' . $filePath . '.php';
        if (!file_exists($fullPath)) {
            throw new \RuntimeException("Template file not found: $fullPath");
        }

        $renderer = new class {
            public function render(string $path, array $vars): string
            {
                extract($vars, EXTR_OVERWRITE);
                ob_start();
                require $path;
                return ob_get_clean();
            }
        };

        return $renderer->render($fullPath, $arrayArgs);
    }

    /**
     * Charge le fichier de constantes
     */
    public function getConstant(): array
    {
        $configFile = realpath(__DIR__ . '/../config/constant.ini');
        if (!$configFile || !file_exists($configFile)) {
            throw new \RuntimeException("Configuration file not found: $configFile");
        }

        return parse_ini_file($configFile, true);
    }

    /**
     * Redirection HTTP
     */
    public function _redirect(string $url, ?string $code = null): void
    {
        // Utilisation de la classe Session au lieu de get_session()
        $session = new Session();
        $session->save();

        if ($code === '301') {
            header('HTTP/1.1 301 Moved Permanently');
        } elseif ($code === '307') {
            header('HTTP/1.1 307 Moved Temporarily');
        }

        header("Location: $url");
        exit;
    }
}
