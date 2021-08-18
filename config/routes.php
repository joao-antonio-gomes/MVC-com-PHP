<?php

use Alura\Cursos\Controller\{Deslogar,
    ExclusaoCurso,
    FormularioEdicao,
    FormularioInsercao,
    FormularioLogin,
    ListarCursos,
    PersistenciaCurso,
    RealizarLogin};

require __DIR__ . '/../vendor/autoload.php';

$rotas = [
    '/listar-cursos' => ListarCursos::class,
    '/novo-curso' => FormularioInsercao::class,
    '/salvar-curso' => PersistenciaCurso::class,
    '/excluir-curso' => ExclusaoCurso::class,
    '/editar-curso' => FormularioEdicao::class,
    '/login' => FormularioLogin::class,
    '/realiza-login' => RealizarLogin::class,
    '/logout' => Deslogar::class
];

return $rotas;