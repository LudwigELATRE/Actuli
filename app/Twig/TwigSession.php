<?php

namespace App\Twig;


use Twig\TwigFunction;

class TwigSession extends \Twig\Extension\AbstractExtension implements \Twig\Extension\GlobalsInterface
{
    public function getGlobals(): array
    {
        return [
            'text' => $_SESSION,
        ];
    }

}