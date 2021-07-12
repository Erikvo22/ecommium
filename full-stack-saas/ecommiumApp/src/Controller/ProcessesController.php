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

    public function __construct(DocumentManager $documentManager)
    {
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
        foreach ($processes as $p) {
            $arrayAux = array(
                'id' => $p->getId(),
                'input' => $p->getInput(),
                'output' => $p->getOutput(),
                'createdAt' => $p->getCreatedAt()->format('Y-m-d H:i:s'),
                'updatedAt' => $p->getStartedAt() ? $p->getStartedAt()->format('Y-m-d H:i:s') : null,
                'finishedAt' => $p->getFinishedAt() ? $p->getFinishedAt()->format('Y-m-d H:i') : null,
                'status' => $p->getStatus()
            );
            array_push($arrayData, $arrayAux);
        }
        return new Response(json_encode(array(
            'processes' => $arrayData
        )), 200);
    }

    /**
     * @Route("/create", methods="POST")
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function createAction(Request $request): Response
    {
        try {
            $data = $request->getContent();
            $data = json_decode($data);
            $input = isset($data->input) ? $data->input : '';
            $action = isset($data->action) ? $data->action : 0;

            $process = new Processes();
            $process->setInput($input);
            $process->setStatus((int)$action);
            $process->touchCreatedAt();
            $this->documentManager->persist($process);
            $this->documentManager->flush();
        } catch (Exception $e) {
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

    /**
     * @Route("/run", methods="PUT")
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function runAction(Request $request): Response
    {
        try {
            $data = $request->getContent();
            $data = json_decode($data);
            $id = isset($data->id) ? $data->id : null;
            if ($id) {
                $process = $this->documentManager->getRepository(Processes::class)->find($id);
                if ($process) {
                    $inputToLower = strtolower($process->getInput());
                    $output = substr_count($inputToLower, 'a') +
                        substr_count($inputToLower, 'e') +
                        substr_count($inputToLower, 'i') +
                        substr_count($inputToLower, 'o') +
                        substr_count($inputToLower, 'u');

                    $process->setOutput($output);
                    $process->touchFinishedAt();
                    $process->setStatus(2);
                    $this->documentManager->flush();
                } else {
                    return new Response(json_encode(array(
                        'error' => true,
                        'msg' => 'Process not found'
                    )), 400);
                }
            } else {
                return new Response(json_encode(array(
                    'error' => true,
                    'msg' => 'Id not found'
                )), 400);
            }
        } catch (Exception $e) {
            return new Response(json_encode(array(
                'error' => true,
                'msg' => 'An error has ocurried'
            )), 400);
        }
        return new Response(json_encode(array(
            'error' => false,
            'msg' => 'Process finished with id ' . $process->getId()
        )), 200);
    }
}
