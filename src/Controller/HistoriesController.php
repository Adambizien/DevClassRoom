<?php

namespace App\Controller;

use App\Entity\Histories;
use App\Form\HistoriesType;
use App\Repository\HistoriesRepository;
use App\Repository\TutorialsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/histories')]
class HistoriesController extends AbstractController
{
    #[Route('/', name: 'app_histories_index', methods: ['GET'])]
    public function historiesR(HistoriesRepository $historiesRepository): Response
    {
        $histories = $historiesRepository->getAllHistoriesByUserIdWithTutorialStatusOn($this->getUser()->getId());

        $porgression = [];
        foreach ($histories as $historie) {
            if(count($historie->getTutorials()->getChapter()) === 0){
                $porgression[] = 100;
                continue;
            }
            $porgression[] =  (count($historie->getProgression()) /(count($historie->getTutorials()->getChapter()) + 1)) * 100;
        }
        foreach ($histories as $historie) {
            $description = $this->removeFormatIndicateur($historie->getTutorials()->getDescription());
            $historie->getTutorials()->setDescription($description);
        }
        
        return $this->render('histories/index.html.twig', [
            'histories' => $histories,
            'porgression' => $porgression,
        ]);
    }

    private function removeFormatIndicateur(string $description): string
    {
        $description = preg_replace('/^### (.*)$/m', '$1', $description);
        
        $description = preg_replace('/^#### (.*)$/m', '$1', $description);

        $description = preg_replace('/\*\*(.*?)\*\*/', '$1', $description);

        $description = preg_replace('/~~/', '', $description);

        $description = preg_replace('/♠/', '', $description);


        $description = preg_replace('/♣/', '', $description);

        $description = preg_replace('/♥/', '', $description);

        $description = preg_replace('/♦/', '', $description);

        $description = nl2br($description);


        return $description;
    }

    #[Route('/{id}/current/{currentChapterId}/next/{chapterId}', name: 'app_histories', methods: ['GET','POST'])]
    public function historiesCU($id,$currentChapterId,$chapterId,HistoriesRepository $historiesRepository, EntityManagerInterface $entityManager,TutorialsRepository $tutorialsRepository): Response
    {
        if($this->getUser() === null){
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }
        $user = $this->getUser();
        $userId = $user->getId();
        $historie = $historiesRepository->getHistoriesByUserIdAndTutorialId($userId,$id);
        $today = new \DateTimeImmutable();
        if($historie){
            $historie = $historie[0]; 
            $progression = $historie->getProgression();

            if (!$progression) {
                $progression = [];
            }

            if (!in_array($currentChapterId, $progression)) {
                $progression[] = $currentChapterId;
            }
            $historie->setProgression($progression);

            $historie->setUpdatedAt($today);

            $entityManager->persist($historie);
            $entityManager->flush();
        }else{
            $historie = new Histories();
            $historie->setUsers($user);
            $tutorial = $tutorialsRepository->findTutorialById($id);
            $historie->setTutorials($tutorial);
            $progression = [];
            $progression[] = $currentChapterId;
            $historie->setProgression($progression);
            $historie->setCreatedAt($today);
            $historie->setUpdatedAt($today);
            $entityManager->persist($historie);
            $entityManager->flush();
        }
        if($chapterId == -2){
            return $this->redirectToRoute('app_formation_finish', ['id' => $id], Response::HTTP_SEE_OTHER);
        }
        return $this->redirectToRoute('app_formation_chapter_show', ['id' => $id,'chapterId' => $chapterId], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{historieId}/remove', name: 'app_histories_remove', methods: ['GET','POST'])]
    public function historiesRm($historieId,HistoriesRepository $historiesRepository, EntityManagerInterface $entityManager): Response
    {
        $historie = $historiesRepository->find($historieId);
        $entityManager->remove($historie);
        $entityManager->flush();
        return $this->redirectToRoute('app_histories_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/removeAll', name: 'app_histories_removeAll', methods: ['GET','POST'])]
    public function historiesRmAll(HistoriesRepository $historiesRepository, EntityManagerInterface $entityManager): Response
    {
        $histories = $historiesRepository->getAllHistoriesByUserIdWithTutorialStatusOn($this->getUser()->getId());
        foreach ($histories as $historie) {
            $entityManager->remove($historie);
        }
        $entityManager->flush();
        return $this->redirectToRoute('app_histories_index', [], Response::HTTP_SEE_OTHER);
    }

}
