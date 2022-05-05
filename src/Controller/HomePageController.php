<?php
/**
 * HomePage controller.
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomePageController
 */
class HomePageController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="homepage_index",
     * )
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     */
    public function index(Request $request): Response
    {
        return $this->render(
            'homepage/index.html.twig',
            []
        );
    }
}