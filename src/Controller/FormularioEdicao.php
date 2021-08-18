<?php

namespace Alura\Cursos\Controller;

use Alura\Cursos\Entity\Curso;
use Alura\Cursos\Helper\FlashMessageTrait;
use Alura\Cursos\Helper\RenderizadorDeHtmlTrait;
use Alura\Cursos\Infra\EntityManagerCreator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FormularioEdicao implements RequestHandlerInterface
{
    use RenderizadorDeHtmlTrait, FlashMessageTrait;
    
    /**
     * @var ObjectRepository
     */
    private $repositorioCursos;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repositorioCursos = $entityManager->getRepository(Curso::class);
    }
    
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $requestString = $request->getQueryParams();
        $id = \filter_var($requestString['id'], \FILTER_VALIDATE_INT);
    
        $resposta = new Response(302, ['Location' => '/listar-cursos']);
        if (\is_null($id) || $id === false) {
            $this->defineMensagem('danger', 'ID de curso inválido!');
            return $resposta;
        }
    
        $curso = $this->repositorioCursos->find($id);
        $html = $this->renderizaHtml('cursos/formulario.php', [
            'curso' => $curso,
            'titulo'=>  "Editar curso de " . $curso->getDescricao()
        ]);
        return new Response(200, [], $html);
    }
}