<?php

namespace Astina\Bundle\RedirectManagerBundle\EventListener;

use Astina\Bundle\RedirectManagerBundle\Redirect\RedirectFinderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Twig\Environment;

/**
 * Class RedirectListener
 *
 * @package   Astina\Bundle\RedirectManagerBundle\EventListener
 * @author    Matej Velikonja <mvelikonja@astina.ch>
 * @author    Philipp Kräutli <pkraeutli@astina.ch>
 * @copyright 2013 Astina AG (http://astina.ch)
 */
class RedirectListener extends AbstractController
{
    /**
     * @var RedirectFinderInterface
     */
    private $redirectFinder;

    /**
     * @param RedirectFinderInterface $redirectFinder
     */
    public function __construct(RedirectFinderInterface $redirectFinder)
    {
        $this->redirectFinder = $redirectFinder;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();

        if ($request->getMethod() != 'GET') {
            return;
        }

        $responseData = $this->redirectFinder->findRedirect($request);

        if (null === $responseData) {
            return;
        }

        $statusCode = $responseData['statusCode'];

        if ( !($statusCode >= 300 && $statusCode < 400) ) {
            $response = new Response('', $statusCode);
        } else {
            $response = new RedirectResponse($responseData['redirectToUrl'], $statusCode);
        }

        $event->setResponse($response);
    }
}
