<?php

namespace App\Controller;

use App\Document\Processes;
use Doctrine\ODM\MongoDB\DocumentManager;
use PHPUnit\Util\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
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
                'startedAt' => $p->getStartedAt() ? $p->getStartedAt()->format('Y-m-d H:i:s') : null,
                'finishedAt' => $p->getFinishedAt() ? $p->getFinishedAt()->format('Y-m-d H:i:s') : null,
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
            $type = isset($data->type) ? (int)$data->type : 0;
            $input = isset($data->input) ? $data->input : '';

            $process = new Processes();
            $process->setType($type);
            $process->setInput($input);
            $process->setStatus(0);
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
            'msg' => 'Created process id ' . $process->getId(),
            'id' => $process->getId()
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
            $output = null;
            if ($id) {
                $process = $this->documentManager->getRepository(Processes::class)->find($id);
                if ($process) {
                    if($process->getType() === 1) { //VOWELS_COUNT
                        $inputWithoutSpaces = str_replace(' ', '', $process->getInput());
                        $processAction = new Process(['node', 'vowels.js', $inputWithoutSpaces], getcwd() . '\processes');
                        $processAction->start();

                        $process->touchStartedAt();
                        $process->setStatus(1);
                        $this->documentManager->flush();

                        $processAction->wait();

                        $output = $processAction->getOutput();
                    }
                    $process->setOutput((int)$output);
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
