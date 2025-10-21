<?php

declare(strict_types=1);

namespace FwTest\Core;

/**
 * Class Router
 * Gère les routes basées sur les annotations @Route
 */
class Router
{
    /**
     * Initialise le routeur et tente de trouver une route correspondante
     *
     * @throws \Exception si aucune route ne correspond
     */
    public static function init(): void
    {
        $controllerDir = realpath(__DIR__ . '/../controllers');

        if (!$controllerDir) {
            throw new \Exception("Controllers directory not found");
        }

        $controllerFiles = glob($controllerDir . '/*.php');

        if (empty($controllerFiles)) {
            throw new \Exception('No controllers found');
        }

        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        $currentUri = '/' . ltrim(str_replace($basePath, '', $requestUri), '/');

        foreach ($controllerFiles as $filePath) {
            if (self::matchRouteInFile($filePath, $currentUri)) {
                return; // route trouvée
            }
        }

        throw new \Exception("No matching route for URI: {$currentUri}");
    }

    /**
     * Parcourt un fichier contrôleur et exécute la méthode correspondant à la route
     *
     * @param string $path chemin du fichier contrôleur
     * @param string $currentUri URI demandée
     * @return bool true si une route a été trouvée
     */
    private static function matchRouteInFile(string $path, string $currentUri): bool
    {
        $content = file_get_contents($path);

        if (!preg_match('/class\s+([A-Za-z0-9_]+)/', $content, $matches)) {
            return false;
        }

        $className = $matches[1];
        $fullClass = "\\FwTest\\Controller\\{$className}";

        if (!class_exists($fullClass)) {
            require_once $path;
            if (!class_exists($fullClass)) {
                return false;
            }
        }

        $reflection = new \ReflectionClass($fullClass);

        foreach ($reflection->getMethods() as $method) {
            $doc = $method->getDocComment();

            if ($doc && preg_match('/@Route\([\'"]([^\'"]+)[\'"]\)/', $doc, $routeMatch)) {
                $route = $routeMatch[1];

                if ($route === $currentUri) {
                    $controller = new $fullClass();
                    $methodName = $method->getName();
                    $controller->$methodName();
                    return true;
                }
            }
        }

        return false;
    }
}
