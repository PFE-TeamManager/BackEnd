<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


class DashboardController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    private $data;

    public function __construct(EntityManagerInterface $entityManager,array $data = [])
    {
        $this->entityManager = $entityManager;
        $this->data = $data;
    }

    /**
     * @Route("/api/dashboard/projects/tasks", name="dashboard_projects_tasks")
     */
    public function dashboardProjectsTasks()
    {
        $data = $this->data;
        $projectName = [];
        $countTasks = [];
        $taskRepository = $this->entityManager->getRepository(Task::class);
        $tasksWithProjects = $taskRepository->countTasksWithProjects();

        foreach($tasksWithProjects as $key => $value){
            // $data[$key]['projectName'] = $value["projectName"];
            // $data[$key]['countTasks'] = $value["countTask"];
            array_push($projectName,$value["projectName"]);
            array_push($countTasks,$value["countTask"]);
        }

        $response = array(
            //"dataCountTasksProjects" => (isset($data) ? $data : []),
            "dataProjectName" => $projectName,
            "dataCountTasks" => $countTasks
        );

        return new JsonResponse($response);
    }

}
