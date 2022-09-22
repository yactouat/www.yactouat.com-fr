<?php

declare(strict_types=1);

namespace App;

final class EntryPoint
{
    private Conf $_conf;

    public function __construct(string $rootDir)
    {
        $this->_conf = new Conf($rootDir);
    }

    public function respond()
    {
        // checking if shared (and dev if relevant) configurations are properly set
        if (!Conf::checkDevConf() || !Conf::checkSharedConf()) {
            http_response_code(500);
            die("conf KO");
        }
        echo $this->_conf->twig->render("index.html.twig");
    }
}
