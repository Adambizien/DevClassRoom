<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @var Environment
    */
    protected $twig;
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if($exception instanceof HttpException){
            $data = [
                'status' => $exception->getStatusCode(),
                'message' => $exception->getMessage(),
            ];
        }else{
            $err = ['Something went wrong', 'An error occurred', 'Oops! Something went wrong', 'An error occurred, please try again later', 'An error occurred, please contact the administrator'] ;
            $data = [
                'status' => 500,
                'message' => $err[array_rand($err)],
            ];
        }

        if($_ENV['APP_ENV']!=='dev'){
            $event->setResponse(new Response($this->twig->render('error.html.twig',$data), $data['status']));
        }


    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
