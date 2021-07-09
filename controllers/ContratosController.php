<?php

namespace controllers;

final class ContratosController
{
    private $container;
    private $BASE_URL;
    public function __construct($container)
    {
        $this->container = $container; #container de injecao de dependencia
        $this->BASE_URL = $this->container['settings']['BASE_URL'];
    }
    public function listarContratos($request, $response, array $args)
    {
        return $this->container['twig']->render($response, 'contratos/listaContratos.html', [
            'BASE_URL'     =>  $this->BASE_URL
        ]);
    }
}
