<?php

namespace dabio\RestBundle\Controller;

use dabio\RestBundle\Entity\Item;
use dabio\RestBundle\Service\EntityFormatter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ItemController
 *
 * @package RestBundle
 */
class ItemController extends Controller
{
    /**
     * Returns all items in a json array.
     *
     * @return JsonResponse
     */
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

    /**
     * Creates a new item with the given parameters.
     *
     * @param Request $request
     *
     * @return JsonResponse|Response
     */
    public function createAction(Request $request)
    {
        $item = new Item();
        $form = $this->getItemForm($item)->setMethod('POST')->getForm();

        if (!$form->handleRequest($request)->isValid()) {
            // Something went wrong.
            throw new HttpException(422, 'Unprocessable Entity');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($item);
        $em->flush();

        // get url of newly created item
        $itemUrl = $this->generateUrl(
            'dabio_rest_item_read',
            ['id' => $item->getId()]
        );

        return new JsonResponse(
            (new EntityFormatter())->itemArrayFormat($item),
            201,
            ['Location' => $itemUrl]
        );
    }

    /**
     * Gets an item.
     *
     * @param Item $item
     *
     * @return JsonResponse
     */
    public function readAction(Item $item)
    {
        return new JsonResponse(
            (new EntityFormatter())->itemArrayFormat($item)
        );
    }

    /**
     * Updates an item.
     *
     * @param Request $request
     * @param Item    $item
     *
     * @return JsonResponse
     */
    public function updateAction(Request $request, Item $item)
    {
        $form = $this->getItemForm($item)->setMethod('PUT')->getForm();

        if (!$form->submit($request, false)->isValid()) {
            // Something went wrong.
            throw new HttpException(422, 'Unprocessable Entity');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($item);
        $em->flush();

        return new JsonResponse(
            (new EntityFormatter())->itemArrayFormat($item)
        );
    }

    /**
     * HELPERS
     *
     * Find a few helpers below.
     */

    /**
     * Form for creating and editing items.
     *
     * @param Item $item
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    private function getItemForm(Item $item)
    {
        // We use the form.factory service here, so we can use a form without
        // a name.
        /** @var \Symfony\Component\Form\FormFactory $form */
        $form = $this->get('form.factory');

        return $form
            ->createNamedBuilder('', 'form', $item)
            ->add('name')
            ->add('description')
            ->add('price', 'number');
    }
}
