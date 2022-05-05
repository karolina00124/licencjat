<?php
/**
 * HomePageApp controller.
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomePageController
 */
class HomePageAppController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Route(
     *     "/homepageapp",
     *     methods={"GET"},
     *     name="homepageapp_index",
     * )
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     */
    public function index(Request $request): Response
    {
        return $this->render(
            'homepageapp/index.html.twig',
            []
        );
    }
}
