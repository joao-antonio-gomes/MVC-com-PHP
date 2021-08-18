<?php

namespace Alura\Cursos\Controller;

use Alura\Cursos\Entity\Usuario;
use Alura\Cursos\Helper\FlashMessageTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RealizarLogin implements RequestHandlerInterface
{
    use FlashMessageTrait;
    private ObjectRepository $repositorioDeUsuarios;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repositorioDeUsuarios = $entityManager->getRepository(Usuario::class);
    }
    
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $email = filter_var(
            $request->getParsedBody()['email'],
            FILTER_VALIDATE_EMAIL
        );
        $resposta = new Response(302, ['Location' => '/login']);
        if ($email === false || \is_null($email)) {
            $this->defineMensagem('danger', "E-mail inválido!");
            return $resposta;
        }
        
        $senha = \filter_var($request->getParsedBody()['senha'], \FILTER_SANITIZE_STRING);
        /** @var Usuario $usuario */
        $usuario = $this->repositorioDeUsuarios->findOneBy(['email'=>$email]);
        
        if (\is_null($usuario) || !$usuario->senhaEstaCorreta($senha)) {
            $this->defineMensagem('danger', 'E-mail ou senha inválidos!');
            return $resposta;
        }
        
        $_SESSION['logado'] = true;
        
        return new Response(302, ['Location' => '/listar-cursos']);
    }
    
}