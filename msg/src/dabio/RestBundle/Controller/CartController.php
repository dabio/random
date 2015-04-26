<?php

namespace dabio\RestBundle\Controller;

use dabio\RestBundle\Entity\Cart;
use dabio\RestBundle\Service\EntityFormatter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class CartController
 *
 * @package RestBundle
 */
class CartController extends Controller
{
    /**
     * This is a arbitrary customer id. The customer id should be saved inside
     * a session.
     */
    const CUSTOMER_ID = '6e2baaf3b97dbeef01c0043275f9a0e7';

    /**
     * Lists all cart objects.
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $carts = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('dabioRestBundle:Cart')
            ->findBy(['customerId' => self::CUSTOMER_ID]);

        return new JsonResponse(
            (new EntityFormatter())->cartsArrayFormat($carts)
        );
    }

    /**
     * Creates a new cart.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $cart = (new Cart())
            ->setCustomerId(self::CUSTOMER_ID);
        $form = $this->getCartForm($cart)->setMethod('POST')->getForm();

        if (!$form->handleRequest($request)->isValid()) {
            // Something went wrong.
            throw new HttpException(422, 'Unprocessable Entity');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($cart);
        $em->flush();

        // get url of newly created item
        $cartUrl = $this->generateUrl(
            'dabio_rest_cart_read',
            ['id' => $cart->getId()]
        );

        return new JsonResponse(
            (new EntityFormatter())->cartArrayFormat($cart),
            201,
            ['Location' => $cartUrl]
        );
    }

    /**
     * HELPERS
     *
     * Find a few helpers below.
     */

    /**
     * Form for creating carts.
     *
     * @param Cart $cart
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    private function getCartForm(Cart $cart)
    {
        // We use the form.factory service here, so we can use a form without
        // a name.
        /** @var \Symfony\Component\Form\FormFactory $form */
        $form = $this->get('form.factory');

        return $form
            ->createNamedBuilder('', 'form', $cart)
            ->add('item', 'entity', [
                'class' => 'dabioRestBundle:Item',
                'property' => 'id',
            ]);
    }
}
