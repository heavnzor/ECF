<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Dto\BatchActionDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // set this option if you prefer the page content to span the entire
            // browser width, instead of the default design which sets a max width
            ->renderContentMaximized()

            // set this option if you prefer the sidebar (which contains the main menu)
            // to be displayed as a narrow column instead of the default expanded design
            ->renderSidebarMinimized()
            ->setSearchFields(['id', 'nom', 'prenom', 'description', 'isPostulant', 'isPostulant']);
    }
    public function approveUsers(BatchActionDto $batchActionDto, UserRepository $userRepository)
    {
        $entityManager = $this->container->get('doctrine')->getManagerForClass($batchActionDto->getEntityFqcn());
        foreach ($batchActionDto->getEntityIds() as $id) {
            $user = $userRepository->find($id);
            if ($user->getIsPostulant() !== null && $user->getPrenom() !== null && $user->getNom() !== null && $user->getDescription() !== null && $user->getPhoto() !== null) {
                $user->setIsPostulant(true);
                $user->setRoles(array('ROLE_INSTRUCTEUR'));
                $user->setIsPostulantVerified(true);
                $entityManager->flush();
            }
        }

       

        return $this->redirect($batchActionDto->getReferrerUrl());
    }

    public function configureFields(string $pageName): iterable
    {
        $roles = ['ROLE_ADMIN', 'ROLE_INSTRUCTEUR', 'ROLE_USER'];
        return [
            IdField::new('id'),
            TextField::new('email'),
            TextField::new('pseudo'),
            TextField::new('nom'),
            TextField::new('prenom'),
            TextareaField::new('description'),
            BooleanField::new('isPostulant'),
            BooleanField::new('isPostulantVerified'),
            ChoiceField::new('roles')
                ->setChoices(array_combine($roles, $roles))
                ->allowMultipleChoices()
                ->renderAsBadges()
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
    

        // in PHP 7.4 and newer you can use arrow functions
        // ->displayIf(fn ($entity) => $entity->isPaid())

        return $actions
            // ...
            ->addBatchAction(Action::new('approve', "Approuver l'instructeur")
            ->linkToCrudAction('approveUsers')
            ->addCssClass('btn btn-primary')
            ->setIcon('fa fa-user-check'))
            ->add(Crud::PAGE_INDEX, ACTION::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ->reorder(Crud::PAGE_INDEX, [Action::DETAIL, Action::EDIT, Action::DELETE]);

    }
}
