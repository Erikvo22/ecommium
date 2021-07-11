<?php

namespace App\Controller;

use App\Document\Processes;
use Doctrine\ODM\MongoDB\DocumentManager;
use PHPUnit\Util\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProcessesController extends AbstractController
{
    private $documentManager;

    public function __construct(DocumentManager $documentManager) {
        $this->documentManager = $documentManager;
    }
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $processes = $this->documentManager->getRepository(Processes::class)->findAllOrderedByCreatedAt();
        var_dump($processes);

        return $this->render('index/index.html.twig', [
            'controller_name' => 'ProcessesController',
            'data'            => $processes
        ]);
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function createAction(Request $request) : Response
    {
        try {
            $process = $request->request->all();
            $process = new Processes();
            $process->setInput('prueba');
            $process->setOutput('3');
            $process->setStatus('0');
            $process->touchCreatedAt();
            $process->touchUpdatedAt();

            $this->documentManager->persist($process);
            $this->documentManager->flush();
        }catch (Exception $e){
            return new Response(json_encode(array(
                'error' => true,
                'msg' => 'The process could not be created'
            )), 400);
        }
        return new Response(json_encode(array(
            'error' => false,
            'msg' => 'Created process id ' . $process->getId()
        )), 200);
    }
}
