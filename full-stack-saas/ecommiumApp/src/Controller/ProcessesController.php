<?php

namespace App\Controller;

use App\Document\Processes;
use Doctrine\ODM\MongoDB\DocumentManager;
use PHPUnit\Util\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        return $this->render('index/index.html.twig');
    }

    /**
     * @Route("/data", methods="GET")
     */
    public function getAllProcessesAction()
    {
        $processes = $this->documentManager->getRepository(Processes::class)->findAllOrderedByCreatedAt();
        $arrayData = [];
        foreach ($processes as $p){
            $arrayAux = array(
                'id'         => $p->getId(),
                'input'      => $p->getInput(),
                'output'     => $p->getOutput(),
                'createdAt'  => $p->getCreatedAt()->format('Y-m-d H:i:s'),
                'updatedAt'  => $p->getUpdatedAt()->format('Y-m-d H:i:s'),
                'finishedAt' => $p->getFinishedAt() ? $p->getFinishedAt()->format('Y-m-d H:i') : null,
                'status'     => $p->getStatus()
            );
            array_push($arrayData, $arrayAux);
        }
        return new JsonResponse(array('processes' => $arrayData), 200);
    }

    /**
     * @Route("/create", methods="POST")
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
