<?php
namespace App\Listener;
use Imperium\Config\iConfig;

use Symfony\Component\HttpFoundation\RequestStack;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Security;
use Imperium\Models\userLog\Functions as ulFunctions;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use App\Builder\UserBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Imperium\Models\One\User as UseAuth;
use App\Manager\CookiesManager;
use Predis\Client as Redis;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use ImperiumApp\Manager\AuthManager;
use Imperium\Apps\Menu\Functions as MFunctions;
use ImperiumApp\Manager\BlockManager;
use Imperium\Models\Utils\StaticUtils;
use Imperium\Database\TraitementData\Middleware\Abonnement as AboMiddleWare;
use Imperium\Models\Auth\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RequestHttp implements EventSubscriberInterface
{

    private $request;
    private $authManager;
    private $router;

    public function __construct(TokenStorageInterface $tokenStorage,RequestStack $requestStack,  RouterInterface $router)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
    }

    public function onKernelRequest(FilterControllerEvent $event){
        
        dd($event);
        // if($event->isMasterRequest()){
        //     $user = $this->tokenStorage->getToken()->getUser();            
        //     if($user == 'anon.' && $this->request->isXmlHttpRequest()){
        //         $response = new JsonResponse();
        //         $response->setContent(json_encode(['message'=>'session expired']) );
        //         $response->setStatusCode(401);

        //         // sets a HTTP response header
        //         $response->headers->set('Content-Type', 'application/json');

        //         // prints the HTTP headers followed by the content
        //         $response->send();
        //         exit();
        //     }            
        // }
        // return;
    }

    public static function getSubscribedEvents(){
        return array(
            KernelEvents::CONTROLLER => 'onKernelRequest'
        );
    }
}
