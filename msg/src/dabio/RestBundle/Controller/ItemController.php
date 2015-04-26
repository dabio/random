<?php

namespace dabio\RestBundle\Controller;

use dabio\RestBundle\Service\EntityFormatter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ItemController
 *
 * @package RestBundle
 */
class ItemController extends Controller
{
    public function listAction()
    {
        $items = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('dabioRestBundle:Item')
            ->findAll();

        return new JsonResponse(
            (new EntityFormatter())->itemsArrayFormat($items)
        );
    }
}
