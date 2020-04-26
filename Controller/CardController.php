<?php
/**
 * @copyright   2020
 *
 * @author      Idea2
 *
 * @see        https://www.idea2.ch
 */

namespace MauticPlugin\Idea2TrelloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Constraints as Assert;
use Mautic\CoreBundle\Controller\FormController;

use MauticPlugin\Idea2TrelloBundle\Openapi\Model\NewCard;
use MauticPlugin\Idea2TrelloBundle\Form\NewCardType;

class CardController extends FormController
{
    public function indexAction($page = 1)
    {
        return $this->delegateView(
            [
                'contentTemplate' => 'Idea2TrelloBundle:Hello:index.html.php',
            ]
        );
    }

    /**
     * Build and Handle a new card
     *
     * @param [type] $contactId
     *
     * @return void
     */
    public function addAction($contactId = null)
    {
        $logger = $this->get('monolog.logger.mautic');
        $request = $this->get('request_stack')->getCurrentRequest();

        $logger->warning('got request with id', [$contactId]);
        // $logger->warning('request', );

        // creates a card and gives it some dummy data for this example
        $card = new NewCard();
        $card->setName('Write a blog post');
        $card->setDue(new \DateTime('tomorrow'));

        $form = $this->createForm(NewCardType::class, $card);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $newCard = $form->getData();

            $valid = $this->validateRequestData($newCard);
            if ($valid !== true) {
                return $valid;
            }
            
            // ... perform some action, such as saving the task to the database

            // return $this->redirectToRoute('task_success');
        }

        return $this->delegateView(
            [
                'viewParameters' => [
                    'form' => $form->createView(),
                ],
                'contentTemplate' => 'Idea2TrelloBundle:Card:new.html.twig',
            ]
        );
        // return $this->render('Idea2TrelloBundle:Card:new.html.twig', [
        //     'form' => $form->createView(),
        // ]);
    }

    /**
     * Validates to true or Returns an Error Response
     *
     * @param Request $request
     *
     * @return true || Response
     */
    protected function validateRequestData(NewCard $newCard)
    {
        // Validate the input values
        // $asserts = [];
        // $asserts[] = new Assert\NotNull();
        // $asserts[] = new Assert\Type('MauticPlugin\\Idea2TrelloBundle\\Openapi\\Model\\NewCard');
        // $asserts[] = new Assert\Valid();
        // $response = $this->validate($newCard, $asserts);
        // if ($response instanceof Response) {
        //     return $response;
        // }
        if (empty($newCard->getName())) {
            return new Response('Name not set', 400);
        }

        return true;
    }
}
