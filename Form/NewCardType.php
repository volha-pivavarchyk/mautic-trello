<?php

declare(strict_types=1);
/**
 * @copyright   2020
 * @author      Idea2
 *
 * @see        https://www.idea2.ch
 */

namespace MauticPlugin\Idea2TrelloBundle\Form;

use MauticPlugin\Idea2TrelloBundle\Openapi\lib\Model\NewCard;
use MauticPlugin\Idea2TrelloBundle\Openapi\lib\Model\TrelloList;
use MauticPlugin\Idea2TrelloBundle\Service\TrelloApiService;
use Monolog\Logger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewCardType extends AbstractType
{
    /**
     * @var TrelloApiService
     */
    private $apiService;
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * Setup NewCard Form.
     */
    public function __construct(TrelloApiService $trelloApiService, Logger $logger)
    {
        $this->apiService = $trelloApiService;
        $this->logger = $logger;
    }

    /**
     * Define fields to display.
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('due', DateType::class)
            ->add('idList', ChoiceType::class, [
                'choices' => $this->getListsOnBoard(),
                'choice_value' => 'id',
                'choice_label' => 'name',
            ])
            ->add('save', SubmitType::class, ['label' => 'Create Card'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NewCard::class,
        ]);
    }

    protected function getListsOnBoard($boardId = null)
    {
        $api = $this->apiService->getApi();

        if (empty($boardId)) {
            $boardId = $this->apiService->getFavouriteBoard();
            if (empty($boardId)) {
                $this->logger->warning('no board configured');

                return [];
            }
        }

        try {
            return $api->getLists($boardId);
        } catch (Exception $e) {
            echo 'Exception when calling DefaultApi->getLists: ', $e->getMessage(), PHP_EOL;
        }
        // return [
        //     new TrelloList([
        //         'id' => '5e5c1f8f49c26f3ef8b6eba4',
        //         'name' => '1. Lead',
        //         'pos' => 65535,
        //     ]),
        //     new TrelloList([
        //         'id' => '5e5c1f9aa8fe55462a918ceb',
        //         'name' => '2. Lead Magnet',
        //         'pos' => 131071,
        //     ]),
        // ];
    }
}
