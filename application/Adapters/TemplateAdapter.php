<?php

namespace Agencia\Close\Adapters;

use Agencia\Close\Adapters\Twig\MonthTranslate;
use Agencia\Close\Adapters\Twig\TypeColor;
use Agencia\Close\Adapters\Twig\TypeIcon;
use Agencia\Close\Adapters\Twig\DataDiff;
use Agencia\Close\Adapters\Twig\FilterHash;
use Agencia\Close\Helpers\String\Strings;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
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