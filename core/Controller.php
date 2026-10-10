<?php

namespace Core;

class Controller
{
    protected function render($view, $data = [])
    {
        extract($data);
        
        $viewFile = ROOT . "/app/Views/{$view}.php";
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("View {$view} not found.");
        }
    }

    protected function redirect($url)
    {
        // If absolute URL, just redirect
        if (preg_match('#^https?://#i', $url)) {
            header("Location: {$url}");
            exit;
        }

        // Use the auto-detected base path so the app works from any folder,
        // domain or subdirectory without hardcoding paths. Fallback to APP_URL
        // only when running outside the web entry point (e.g. CLI).
        if (defined('BASE_URL')) {
            $baseUrl = BASE_URL;
        } else {
            $baseUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
        }

        $redirectUrl = $baseUrl . '/' . ltrim($url, '/');
        header("Location: {$redirectUrl}");
        exit;
    }

    protected function json($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    protected function auth()
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }
        return true;
    }

    protected function adminAuth()
    {
        if (!Session::has('admin_id') && Session::get('user_role') !== 'admin') {
            $this->redirect('/login');
        }
        return true;
    }
    
    protected function ownerAuth()
    {
        if (!Session::has('owner_id')) {
            $this->redirect('/owner/login');
        }
        return true;
    }
}
