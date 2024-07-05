<?php

namespace App\Controller;

use App\Form\FormationType;
use App\Repository\ChapterRepository;
use App\Repository\ContentRepository;
use App\Repository\HistoriesRepository;
use App\Repository\TutorialsRepository;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/formation')]
class FormationController extends AbstractController
{
    #[Route('/', name: 'app_formation')]
    public function index(Request $request,TutorialsRepository $tutorialsRepository): Response
    {
        $form = $this->createForm(FormationType::class);
        $form->handleRequest($request);
        $tutorials = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $searchTerm = $data['search_tutorial_name'] ?? null;
            $categories = $data['categories'] ?? [];
            // dd($searchTerm,$categories);

            // Recherche par nom de formation et/ou catégories
            $tutorials = $tutorialsRepository->findBySearchCriteria($searchTerm, $categories);
        }else{
            $tutorials = $tutorialsRepository->findAllTutorialsWithStastusOn();
        }
        foreach ($tutorials as $tutorial) {
            $description = $this->removeFormatIndicateur($tutorial->getDescription());
            $tutorial->setDescription($description);
        }

        return $this->render('formation/index.html.twig', [
            'tutorials' => $tutorials,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/finish', name: 'app_formation_finish')]
    public function finish($id,TutorialsRepository $tutorialsRepository,ChapterRepository $chapterRepository,HistoriesRepository $historiesRepository): Response
    {
        $tutorial = $tutorialsRepository->findTutorialById($id);
        if(!$tutorial || $tutorial->getStatus() !== 'on'){
            return $this->redirectToRoute('app_formation', [], Response::HTTP_SEE_OTHER);
        }
        $chapter = $chapterRepository->getChapterSortByStepOrderByTutorialId($id);

        $lastchapter = null;
        foreach ($chapter as $key => $value) {
            if($value->getStepOrder() === count($chapter)){
                $lastchapter = $value->getId();
                break;
            }
        }
        $histories = $historiesRepository->getHistoriesByUserIdAndTutorialId($this->getUser()->getId(),$id);
        return $this->render('formation/show.html.twig',[
            'tutorialId' => $id,
            'tutorial' => $tutorial,
            'chapters' => $chapter,
            'introduction' => false,
            'lastchapter' => $lastchapter,
            'finish'=> true,
            'progression' => $histories ? $histories[0]->getProgression() : [],
        ]);
    }

    #[Route('/{id}', name: 'app_formation_show', methods: ['GET'])]
    public function show($id,TutorialsRepository $tutorialsRepository,ChapterRepository $chapterRepository,HistoriesRepository $historiesRepository): Response
    {
        $tutorial = $tutorialsRepository->findTutorialById($id);
        if(!$tutorial || $tutorial->getStatus() !== 'on'){
            return $this->redirectToRoute('app_formation', [], Response::HTTP_SEE_OTHER);
        }
        $chapter = $chapterRepository->getChapterSortByStepOrderByTutorialId($id);

        $formattedDescription = $this->formatDescription($tutorial->getDescription());

        $chapterOne = null;
        foreach ($chapter as $key => $value) {
            if($value->getStepOrder() === 1){
                $chapterOne = $value;
                break;
            }
        }
        $histories = null;
        if($this->getUser() !== null){
            $histories = $historiesRepository->getHistoriesByUserIdAndTutorialId($this->getUser()->getId(),$id); 
        }   


        return $this->render('formation/show.html.twig', [
            'tutorial' => $tutorial,
            'chapters' => $chapter,
            'introduction' => true,
            'description' => $formattedDescription,
            'chapterOne' => $chapterOne,
            'progression' => $histories ? $histories[0]->getProgression() : [],
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
  
    private function formatDescription(string $description): string
    {
        // Remplacer ### par <h3> avec classes Tailwind
        $description = preg_replace('/^### (.*)$/m', '<h3 class="text-2xl mb-2">$1</h3>', $description);
        
        // Remplacer #### par <h4> avec classes Tailwind
        $description = preg_replace('/^#### (.*)$/m', '<h4 class="text-lg mb-1">$1</h4>', $description);

        // Remplacer **bold** par <strong> avec classes Tailwind
        $description = preg_replace('/\*\*(.*?)\*\*/', '<strong class="font-bold">$1</strong>', $description);

        // ♠♣ titre1/retours à la ligne/♣ titre2/retours à la ligne/♣titre3 ♠ pour les listes numérotées
        $description = preg_replace_callback('/♠(.*?)♠/s', function ($matches) {
            $matches[1] = preg_replace('/^\s*♣\s+(.*)/m', '<li>$1</li>', $matches[1]);
            return '<ol class="list-decimal mb-4 space-y-4 list-inside">' . $matches[1] . '</ol>';
        }, $description);

        //♥♦ titre1/retours à la ligne/♦ titre2... ♥  pour les listes à puces
        $description = preg_replace_callback('/♥(.*?)♥/s', function ($matches) {
            $matches[1] = preg_replace('/^\s*♦\s+(.*)/m', '<li >$1</li>', $matches[1]);
            return '<ul class="list-disc ps-5 mt-2 space-y-1 list-inside ">' . $matches[1] . '</ul>';
        }, $description);
       

        // Remplacer ~~ par <hr> avec classes Tailwind
        $description = preg_replace('/~~/', '<hr class="my-4 border-gray-300">', $description);

        // Convertir les retours à la ligne en <br>
        $description = nl2br($description);

        // les br entre les li on les enléve 
        $description = preg_replace('/<\/li>\s*<br\s*\/?>\s*/', '</li>', $description);
       
        return $description;
    }


    #[Route('/{id}/chapter/{chapterId}', name: 'app_formation_chapter_show', methods: ['GET'])]
    public function showChapter($id,$chapterId,TutorialsRepository $tutorialsRepository,ChapterRepository $chapterRepository,ContentRepository $contentRepository ,HistoriesRepository $historiesRepository): Response
    {
        $tutorial = $tutorialsRepository->findTutorialById($id);
        if(!$tutorial || $tutorial->getStatus() !== 'on'){
            return $this->redirectToRoute('app_formation', [], Response::HTTP_SEE_OTHER);
        }

        $chapter = $chapterRepository->findChapterById($chapterId);
        

        if(!$chapter || $chapter->getStatus() !== 'on'){
            return $this->redirectToRoute('app_formation_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        $chapters = $chapterRepository->getChapterSortByStepOrderByTutorialId($id);

        $contents = $contentRepository->findAllContentByChapterIdWithStatusOn($chapterId);

        foreach ($contents as $key => $value) {
            if($value->getContentType() === 'text'){
                $contents[$key]->setText($this->formatDescription($value->getText()));
            }
        }

        $nextChapterId = null;
        $previousChapterId = null;
        foreach( $chapters as $key => $value){
            if($chapter->getStepOrder() < count($chapters) && $value->getStepOrder() === $chapter->getStepOrder() + 1){
                $nextChapterId = $value->getId();
            }
            if($chapter->getStepOrder() > 1 && $value->getStepOrder() === $chapter->getStepOrder() - 1){
                $previousChapterId = $value->getId();
            }
        }
        $histories = $historiesRepository->getHistoriesByUserIdAndTutorialId($this->getUser()->getId(),$id);
        return $this->render('formation/show.html.twig', [
            'tutorial' => $tutorial,
            'currentchapter' => $chapter,
            'contents' => $contents,
            'introduction' => false,
            'chapters' => $chapters,
            'nextChapterId' => $nextChapterId,
            'previousChapterId' => $previousChapterId,
            'progression' => $histories ? $histories[0]->getProgression() : [],
        ]);
    }

   

    
}
