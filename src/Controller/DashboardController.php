<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Bug;
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

    /**
     * @Route("/api/dashboard/projects/bugs", name="dashboard_projects_bugs")
     */
    public function dashboardProjectsBugs()
    {
        $data = $this->data;
        $projectName = [];
        $countBugs = [];
        $bugRepository = $this->entityManager->getRepository(Bug::class);
        $bugsWithProjects = $bugRepository->countBugsWithProjects();

        foreach($bugsWithProjects as $key => $value){
            // $data[$key]['projectName'] = $value["projectName"];
            // $data[$key]['countTasks'] = $value["countTask"];
            array_push($projectName,$value["projectName"]);
            array_push($countBugs,$value["countBug"]);
        }

        $response = array(
            //"dataCountTasksProjects" => (isset($data) ? $data : []),
            "dataProjectName" => $projectName,
            "dataCountBugs" => $countBugs
        );

        return new JsonResponse($response);
    }

    /**
     * @Route("/api/dashboard/projects/tasks/state", name="dashboard_projects_tasks_state")
     */
    public function dashboardProjectsTasksState()
    {
        $data = $this->data;
        $dataLabels = [];
        $dataSeries = [];
        $taskRepository = $this->entityManager->getRepository(Task::class);

        $tasksWithStateToDo = $taskRepository->countTasksWithStateToDo();
        
        $tasksWithStateDoing = $taskRepository->countTasksWithStateDoing();
        $tasksWithStateDone = $taskRepository->countTasksWithStateDone();

        array_push($dataLabels,"Task To Do");
        array_push($dataSeries,(int) $tasksWithStateToDo[0]["countTaskToDo"]);

        array_push($dataLabels,"Task Doing");
        array_push($dataSeries,(int) $tasksWithStateDoing[0]["countTaskDoing"]);

        array_push($dataLabels,"Task Done");
        array_push($dataSeries,(int) $tasksWithStateDone[0]["countTaskDone"]);

        $response = array(
            "dataLabels" => $dataLabels,
            "dataSeries" => $dataSeries
        );

        return new JsonResponse($response);
    }
}
