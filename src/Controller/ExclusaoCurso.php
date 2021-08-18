<?php

namespace Alura\Cursos\Controller;

use Alura\Cursos\Entity\Curso;
use Alura\Cursos\Helper\FlashMessageTrait;
use Alura\Cursos\Infra\EntityManagerCreator;
use Doctrine\ORM\EntityManagerInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ExclusaoCurso implements RequestHandlerInterface
{
    use FlashMessageTrait;
    
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $requestString = $request->getQueryParams();
        $id = \filter_var($requestString['id'], \FILTER_VALIDATE_INT);
    
        $resposta = new Response(302, ['Location' => '/listar-cursos']);
        if (\is_null($id) || $id === false) {
            $this->defineMensagem('danger', 'Curso inexistente!');
            \header('Location: /listar-cursos');
            return $resposta;
        }
        
        $entidade = $this->entityManager->getReference(Curso::class, $id);
        $this->entityManager->remove($entidade);
        $this->entityManager->flush();
        $this->defineMensagem('success', 'Curso excluído com sucesso!');
    
        return $resposta;
    }
}