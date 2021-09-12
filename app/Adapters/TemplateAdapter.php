<?php

namespace Felmework\Adapters;

use Felmework\Adapters\Twig\MonthTranslate;
use Felmework\Adapters\Twig\FilterHash;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TemplateAdapter
{
    public function __construct()
    {
        $loader = new FilesystemLoader('view');
        $this->twig = new Environment($loader, [
            'cache' => false,
        ]);
        $this->twig->addExtension(new FilterHash());
        $this->twig->addExtension(new MonthTranslate());
        $this->globals();

        return $this->twig;
    }

    public function render($view, array $data = []): string
    {
        return $this->twig->render($view, $data);
    }

    private function globals()
    {
        $this->twig->addGlobal('DOMAIN', DOMAIN);
        $this->twig->addGlobal('PATH', PATH);
        $this->twig->addGlobal('_session', $_SESSION);
        $this->twig->addGlobal('_post', $_POST);
        $this->twig->addGlobal('_get', $_GET);
    }
}