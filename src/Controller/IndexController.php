<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Cours;
use App\Entity\Quizz;
use App\Form\UserType;
use App\Entity\Section;
use App\Form\CoursType;
use App\Form\QuizzType;
use App\Entity\Progress;
use App\Entity\Formation;
use App\Form\SectionType;
use App\Form\ProgressType;
use App\Form\FormationType;
use App\Services\Progression;
use App\Services\FileUploader;
use App\Security\EmailVerifier;
use App\Controller\imgAndSlogan;
use App\Repository\UserRepository;
use App\Repository\CoursRepository;
use App\Repository\QuizzRepository;
use Symfony\Component\Mime\Address;
use App\Form\ChangePasswordFormType;
use App\Repository\SectionRepository;
use App\Repository\ProgressRepository;
use App\Repository\FormationRepository;
use Doctrine\DBAL\Driver\PDO\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\ResetPasswordRequestFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;


class IndexController extends AbstractController
{

    private EmailVerifier $emailVerifier;
    private MailerInterface $mailer;

    public function __construct(EmailVerifier $emailVerifier, MailerInterface $mailer, Progression $progression)
    {
        $this->progression = $progression;
        $this->emailVerifier = $emailVerifier;
        $this->mailer = $mailer;
    }

    #[Route('/', name: 'app_index', methods: ['GET'])]
    public function index(imgAndSlogan $imgAndSlogan,Progression $progression, FormationRepository $formationRepository): Response
    {
        $this->getUser() ? $user = $this->getUser() : $user = new User();
        return $this->render('index.html.twig', [
            'controller_name' => 'IndexController',
            'formations' => $formationRepository->findBy([], ['id' => 'DESC'], 3),
            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
            'user' => $user,
            'progress' => $progression->getProgress()[1],
            'progression' => $progression->getProgress()[2]
        ]);
    }

    #[Route('/formation', name: 'app_formation_index', methods: ['GET'])]
    public function indexFormation(Request $request, Progression $progression, imgAndSlogan $imgAndSlogan, FormationRepository $formationRepository): Response
    {
        $this->getUser() ? $user = $this->getUser() : $user = new User();
        return $this->render('formation/index.html.twig', [
            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
            'user' => $user,
            'formations' => $formationRepository->findAll(),
            'progress' => $progression->getProgress()[1],
            'progression' => $progression->getProgress()[2]
        ]);
    }

    #[Route('/formation/new', name: 'app_formation_new', methods: ['GET', 'POST'])]
    public function newFormation(Request $request, UserInterface $user, UserRepository $userRepository, CoursRepository $coursRepository, QuizzRepository $quizzRepository, SectionRepository $sectionRepository, imgAndSlogan $imgAndSlogan, FormationRepository $formationRepository, FileUploader $fileUploader, SluggerInterface $slugger): Response
    {
        if (!isset($_POST['step']) && $this->isGranted('ROLE_INSTRUCTEUR')) {
            $step = '0';
        } elseif (isset($_POST['step']) && $this->isGranted('ROLE_INSTRUCTEUR')) {
            $step = $_POST['step'];
        }
        switch ($step) {
            case '0':
                $formation = new Formation();
                $form = $this->createForm(FormationType::class, $formation);
                return $this->render('formation/new.html.twig', [
                    'formation' => $formation,
                    'form' => $form->createView(),
                    'img' => $imgAndSlogan->getImg(),
                    'slogan' => $imgAndSlogan->getSlogan(),
                    'user' => $user,
                ]);
            case '1':
                $formation = new Formation();
                $form = $this->createForm(FormationType::class, $formation);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $formation = $form->getData();
                    $imageFile = $form->get('image')->getData();
                    $formationTitre = $form->get('titre')->getData();
                    $formation->setLearnState(0);
                    $formation->setTitre(ucfirst(strtolower($formationTitre)));
                    if ($imageFile) {
                        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                        // this is needed to safely include the file name as part of the URL
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                        try {
                            $imageFile->move(
                                $this->getParameter('photo_directory'),
                                $newFilename
                            );
                        } catch (FileException $e) {
                            // .. handle exception if something happens during file upload
                        }

                        // updates the 'brochureFilename' property to store the PDF file name
                        // instead of its contents
                        $formation->setImage($newFilename);
                    }

                    $formation->addUser($user);
                    $user->addFormationsAuteur($formation);
                    $formationRepository->add($formation);
                    $section = new Section();
                    $form = $this->createForm(SectionType::class, $section);
                    return $this->render('section/new.html.twig', [
                        $this->addFlash('success', "Formation créée !"),
                        'img' => $imgAndSlogan->getImg(),
                        'slogan' => $imgAndSlogan->getSlogan(),
                        'user' => $user,
                        'section' => $section,
                        'form' => $form->createView(),
                        'formations' => $user->getFormations(),
                    ]);
                }

            case '2':
                $section = new Section();
                $form = $this->createForm(SectionType::class, $section);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $formation = $form->get('formation')->getData();
                    $section = $form->getData();
                    $section->setFormation($form->get('formation')->getData());
                    $section->setTitre(ucfirst(strtolower($form->get('titre')->getData())));
                    $user->addSection($section);
                    $sectionRepository->add($section);

                    $cours = new Cours();
                    $form = $this->createForm(CoursType::class, $cours);
                    $formation = $section->getId();
                    return $this->render('cours/new.html.twig', [
                        $this->addFlash('success', "Section créée !"),
                        'img' => $imgAndSlogan->getImg(),
                        'slogan' => $imgAndSlogan->getSlogan(),
                        'user' => $user,
                        'formation' => $sectionRepository->findOneBySectionId($section->getId()),
                        'sections' => $user->getSections(),
                        'form' => $form->createView(),
                    ]);
                }
            case '3':
                $cours = new Cours();
                $this->getUser() ? $user = $this->getUser() : $user = new User();
                $form = $this->createForm(CoursType::class, $cours);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $imageFile = $form->get('image')->getData();
                    $section = $form->get('section')->getData();
                    $formation = $cours->getSection($section);
                    $formation = $section->getFormation($section);
                    $cours->setFormation($formation);
                    if ($imageFile) {
                        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                        // this is needed to safely include the file name as part of the URL
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                        // Move the file to the directory where brochures are stored
                        try {
                            $imageFile->move(
                                $this->getParameter('photo_directory'),
                                $newFilename
                            );
                        } catch (FileException $e) {
                            // .. handle exception if something happens during file upload
                        }

                        // updates the 'brochureFilename' property to store the PDF file name
                        // instead of its contents
                        $cours->setImage($newFilename);
                    }
                    $pdf = $form->get('pdf')->getData();
                    if ($pdf) {
                        $originalFilename = pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME);
                        // this is needed to safely include the file name as part of the URL
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename . '-' . uniqid() . '.' . $pdf->guessExtension();

                        // Move the file to the directory where brochures are stored
                        try {
                            $pdf->move(
                                $this->getParameter('photo_directory'),
                                $newFilename
                            );
                        } catch (FileException $e) {
                            // .. handle exception if something happens during file upload
                        }

                        // updates the 'brochureFilename' property to store the PDF file name
                        // instead of its contents

                        // updates the 'photoname' property to store the PDF file name
                        // instead of its contents
                        $cours->setPdf($newFilename);
                    }
                    $coursTitre = $form->get('titre')->getData();
                    $cours->setTitre(ucfirst(strtolower($coursTitre)));
                    $cours = $form->getData();
                    $video = $form->get('video')->getData();
                    $video = preg_match("/(?<=\d\/|\.be\/|v[=\/])([\w\-]{11,})|^([\w\-]{11})$/im", $video, $match);
                    $cours->setVideo($match[0]);
                    $user->addCours($cours);
                    $coursRepository->add($cours);
                    $section = $form->get('section')->getData();
                    $quizz = new Quizz();
                    $form = $this->createForm(QuizzType::class, $quizz);
                    return $this->render('quizz/new.html.twig', [
                        'img' => $imgAndSlogan->getImg(),
                        'slogan' => $imgAndSlogan->getSlogan(),
                        'user' => $user,
                        'sections' => $user->getSections(),
                        'form' => $form->createView(),
                    ]);
                }
            case '4':
                $quizz = new Quizz();
                $form = $this->createForm(QuizzType::class, $quizz);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $quizzQuestion = $form->get('question')->getData();
                    $quizz->setQuestion(ucfirst(strtolower($quizzQuestion)));
                    $quizz->setSection($sectionRepository->findOneBy(['id' => $form->get('section')->getData()]));
                    $quizzRepository->add($quizz);
                    return $this->render('formation/index.html.twig', [
                        $this->addFlash('success', "Quizz créé !"),
                        'img' => $imgAndSlogan->getImg(),
                        'slogan' => $imgAndSlogan->getSlogan(),
                        'user' => $user,
                        'formations' => $formationRepository->findAll()
                    ]);
                }
        }
        return $this->redirectToRoute('app_index');
    }


    #[Route('/formation/{id}', name: 'app_formation_show', methods: ['GET'])]
    public function showFormation(ManagerRegistry $doctrine, ProgressRepository $progressRepository, FormationRepository $formationRepository, imgAndSlogan $imgAndSlogan, Int $id, Progression $progression): Response
    {
        $this->getUser() ? $user = $this->getUser() : $user = new User();
        $formation = $formationRepository->find($id);
        if ($formation->getAuteur() == $user) {
            $progress = null;
        } else {
            $progressRepository->findOneBy(['user' => $user, 'formation' => $formation]) ? $progress = $progressRepository->findOneBy(['user' => $user, 'formation' => $formation]) : $progress = new Progress();
            $learnState = $progress->getFormationFinished();
            if ($learnState == 0) { // si la formation n'est pas commencée
                $progress->setUser($user);
                $progress->setFormation($formation);
                $progress->setFormationFinished('2'); //set formation à "en cours"
                $progressRepository->add($progress);
            } else {
                $progress = $progressRepository->findOneBy(['formation' => $formation, 'user' => $user]);
            }
        }
        return $this->render('formation/show.html.twig', [
            'formation' => $formationRepository->find($id),
            'sections' => $formationRepository->find($id)->getSection(),
            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
            'user' => $user,
            'formations' => $progression->getProgress()[0],
            'progress' => $progression->getProgress()[1],
            'progression' => $progression->getProgress()[2],
        ]);
    }

    #[Route('formation/{id}/edit', name: 'app_formation_edit', methods: ['GET', 'POST'])]
    public function editFormation(Request $request, SluggerInterface $slugger, FileUploader $fileUploader, Formation $formation, FormationRepository $formationRepository, imgAndSlogan $imgAndSlogan): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        $this->getUser() ? $user = $this->getUser() : $user = new User();
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('image')->getData();
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photo->getExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('photo_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \RuntimeException($e->getMessage);
                }
                $photoFile = $form->get('image')->getData();
                if ($photoFile) {
                    $photoFileName = $fileUploader->upload($photoFile);
                    $formation->setImage($photoFileName);
                }

                // updates the 'photoname' property to store the PDF file name
                // instead of its contents
                $formation->setImage($newFilename);
            }

            $formation->addUser($user);
            $user->addFormation($formation);

            return $this->redirectToRoute('app_formation_index', [

                'img' => $imgAndSlogan->getImg(),
                'slogan' => $imgAndSlogan->getSlogan(),
                'user' => $user
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form,

            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
            'user' => $user
        ]);
    }

    #[Route('cours/', name: 'app_cours_index', methods: ['GET'])]
    public function indexCours(CoursRepository $coursRepository, UserInterface $user, imgAndSlogan $imgAndSlogan): Response
    {
        return $this->render('cours/index.html.twig', [
            'cours' => $coursRepository->findAll(),

            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
            'user' => $user
        ]);
    }

    #[Route('cours/new', name: 'app_cours_new', methods: ['GET', 'POST'])]
    public function newCours(Request $request, UserInterface $user, imgAndSlogan $imgAndSlogan, CoursRepository $coursRepository, FileUploader $fileUploader, SluggerInterface $slugger, SectionRepository $sectionRepository): Response
    {
        $cours = new Cours();
        $sections = $sectionRepository->findAll();
        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            $section = $form->get('section')->getData();
            $formation = $cours->getSection($section);
            $formation = $section->getFormation($section);
            $cours->setFormation($formation);
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('photo_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // .. handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $cours->setImage($newFilename);
            }
            $pdf = $form->get('pdf')->getData();
            if ($pdf) {
                $originalFilename = pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $pdf->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $pdf->move(
                        $this->getParameter('photo_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // .. handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents

                // updates the 'photoname' property to store the PDF file name
                // instead of its contents
                $cours->setPdf($newFilename);
            }
            $coursTitre = $form->get('titre')->getData();
            $cours->setTitre(ucfirst(strtolower($coursTitre)));
            $cours = $form->getData();
            $video = $form->get('video')->getData();
            $video = preg_match("/(?<=\d\/|\.be\/|v[=\/])([\w\-]{11,})|^([\w\-]{11})$/im", $video, $match);
            $cours->setVideo($match[0]);
            $user->addCours($cours);
            $coursRepository->add($cours);
            return $this->redirectToRoute('app_cours_index', [
                'img' => $imgAndSlogan->getImg(),
                'slogan' => $imgAndSlogan->getSlogan(),
                'user' => $user
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cours/new.html.twig', [
            'cours' => $cours,
            'form' => $form,
            'sections' => $sections,
            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
            'user' => $user
        ]);
    }

    #[Route('cours/{id}', name: 'app_cours_show', methods: ['GET'])]
    public function showCours(Cours $cours, imgAndSlogan $imgAndSlogan, ProgressRepository $progressRepository): Response
    {
        $this->getUser() ? $user = $this->getUser() : $user = new User();
        $progressRepository->findOneBy(['user' => $user, 'cours' => $cours]) ? $progress = $progressRepository->findOneBy(['user' => $user, 'cours' => $cours]) : $progress = new Progress();
        $coursProgress = $progress->getCoursFinished();
        if ($coursProgress == 0 || $progress == new Progress()) {
            $progress->setUser($user);
            $progress->setFormation($cours->getSection()->getFormation());
            $progress->setCours($cours);
            $progress->setCoursFinished(2);
            $progressRepository->add($progress);
            $coursProgress = $progress->getCoursFinished();
        } elseif (isset($_GET['f']) && $_GET['f'] == 1) {
            $progress->setCoursFinished(1);
            $progressRepository->add($progress);
            $coursProgress = $progress->getCoursFinished();
            $formation = $cours->getSection()->getFormation();
            $allCoursFormation = $formation->getCours();
            foreach($allCoursFormation as $cours){
                if($progress->getCoursFinished() == 2 || $progress->getCoursFinished() == 0){
                    //formation unfinished
                }else{
                    $progress->setFormationFinished(1);
                    $progressRepository->add($progress);
                }
            }
        }
        return $this->render('cours/show.html.twig', [
            'cours' => $cours,
            'coursProgress' => $coursProgress,
            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
            'user' => $user
        ]);
    }

    #[Route('cours/{id}/edit', name: 'app_cours_edit', methods: ['GET', 'POST'])]
    public function editCours(Request $request, UserInterface $user, Cours $cours, CoursRepository $coursRepository, imgAndSlogan $imgAndSlogan, Int $id): Response
    {
        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $video = $form->get('video')->getData();
            $video = preg_match("/(?<=\d\/|\.be\/|v[=\/])([\w\-]{11,})|^([\w\-]{11})$/im", $video, $match);
            $cours->setVideo($match[0]);
            $coursRepository->add($cours);
            return $this->redirectToRoute('app_cours_index', [
                'user' => $user,
                'img' => $imgAndSlogan->getImg(),
                'slogan' => $imgAndSlogan->getSlogan(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cours/edit.html.twig', [
            'cour' => $cours,
            'form' => $form,
            'sections' => $user->getSections(),
            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
            'user' => $user
        ]);
    }

    #[Route('cours/{id}', name: 'app_cours_delete', methods: ['POST'])]
    public function deleteCours(Request $request, Cours $cours, UserInterface $user, CoursRepository $coursRepository, imgAndSlogan $imgAndSlogan): Response
    {
        if ($this->isCsrfTokenValid('delete' . $cours->getId(), $request->request->get('_token'))) {
            $coursRepository->remove($cours);
        }

        return $this->redirectToRoute('app_cours_index', [

            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
            'user' => $user
        ], Response::HTTP_SEE_OTHER);
    }
   
    


    #[Route('/registration/postulant', name: 'app_postulant')]
    public function registerPostulant(Request $request, UserRepository $userRepository, imgAndSlogan $imgAndSlogan, UserPasswordHasherInterface $userPasswordHasher, FileUploader $fileUploader, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {


        // POSTULATION INSTRUCTEUR  

        $this->getUser() ? $user = $this->getUser() : $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $potentialUser =  $userRepository->findOneBy(['email' => $form->get('email')->getData()]);
            if ($potentialUser == null) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
            } else {
                $user->setPassword($form->get('password')->getData());
            }
            // encode the plain password only if it s a new registration
            $img = $form->get('photo')->getData();
            if ($img) {
                $originalFilename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $img->getExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $img->move(
                        $this->getParameter('photo_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \RuntimeException($e->getMessage);
                }
                $photoFile = $form->get('photo')->getData();
                if ($photoFile) {
                    $photoFileName = $fileUploader->upload($photoFile);
                    $user->setPhoto($photoFileName);
                }
                $user->setPhoto($newFilename);
            }
            $user->setIsPostulant(true);
            $user->setRoles(array('ROLE_USER'));
            if ($potentialUser == null) {
                $user->setEmail($form->get('email')->getData());
                $entityManager->persist($user);
            } else {
                $pseudo = $potentialUser->getPseudo();
                $user->setPseudo($pseudo);
                $user->setEmail($form->get('email')->getData());
            }
            $entityManager->flush();
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('webmaster@waldganger.net', 'Waldganger'))
                    ->to($form->get('email')->getData())
                    ->subject('Accusé de réception de votre postulation')
                    ->htmlTemplate('registration/confirmation_email_postulant.html.twig')
            );
            return $this->redirectToRoute('app_index', [
                $this->addFlash('success', 'Confirmation de votre postulation, un email vous a été envoyé : vérifiez vos spams. '),
                'img' => $imgAndSlogan->getImg(),
                'slogan' => $imgAndSlogan->getSlogan()
            ]);
        }
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        return $this->render('user/new.html.twig', [
            'registrationForm' => $form->createView(),
            'img' => $imgAndSlogan->getImg(),
            'user' => $user,
            'slogan' => $imgAndSlogan->getSlogan(),
            'postulant' => true,
        ]);
    }


    #[Route('registration/register', name: 'app_register')]
    public function register(Request $request, imgAndSlogan $imgAndSlogan, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $this->getUser() ? $user = $this->getUser() : $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user->setRoles(array('ROLE_USER'));
            $user->setEmail($form->get('email')->getData());
            $user->setPseudo($form->get('pseudo')->getData());
            $entityManager->persist($user);
            $entityManager->flush();


            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('webmaster@waldganger.net', 'Waldganger.NET'))
                    ->to($form->get('email')->getData())
                    ->subject('Confirmez votre e-maill')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_index', [
                'slogan' => $imgAndSlogan->getSlogan(),
                'img' => $imgAndSlogan->getImg(),
                'user' => $user,
                $this->addFlash('success', "Vous êtes désormais inscrit. Veuillez désormais confirmez votre adresse e-mail pour pouvoir vous connecter : vérifiez vos spams si vous ne recevez pas l'email."),
                'role' => $user->getRoles()
            ]);
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        return $this->render('user/new.html.twig', [
            'registrationForm' => $form->createView(),
            'slogan' => $imgAndSlogan->getSlogan(),
            'user' => $user,
            'img' => $imgAndSlogan->getImg(),
            'postulant' => false
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason(), [], 'VerifyEmailBundle');

            return $this->redirectToRoute('app_register');
        }
        $user->setIsVerified(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_login', [$this->addFlash('success', "Bienvenue chez les marcheurs forestiers 3.0.")]);
    }
    use ResetPasswordControllerTrait;

    private $resetPasswordHelper;
    private $entityManager;


    /**
     * Display & process form to request a password reset.
     */
    #[Route('', name: 'app_forgot_password_request')]
    public function request(Request $request, MailerInterface $mailer,  $imgAndSlogan): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processSendingPasswordResetEmail($form->get('email')->getData(), $mailer);
        }

        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form->createView(),

            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan()
        ]);
    }

    /**
     * Confirmation page after a user has requested a password reset.
     */
    #[Route('/check-email', name: 'app_check_email')]
    public function checkEmail(imgAndSlogan $imgAndSlogan, UserInterface $user): Response
    {
        // Generate a fake token if the user does not exist or someone hit this page directly.
        // This prevents exposing whether or not a user was found with the given email address or not
        if (null === ($resetToken = $this->getTokenObjectFromSession())) {
            $resetToken = $this->resetPasswordHelper->generateFakeResetToken();
        }

        return $this->render('reset_password/check_email.html.twig', [
            'resetToken' => $resetToken,

            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
            'user' => $user
        ]);
    }

    /**
     * Validates and process the reset URL that the user clicked in their email.
     */
    #[Route('/reset/{token}', name: 'app_reset_password')]
    public function reset(Request $request, imgAndSlogan $imgAndSlogan, UserPasswordHasherInterface $userPasswordHasher, string $token = null): Response
    {
        if ($token) {
            // We store the token in session and remove it from the URL, to avoid the URL being
            // loaded in a browser and potentially leaking the token to 3rd party JavaScript.
            $this->storeTokenInSession($token);

            return $this->redirectToRoute('app_reset_password');
        }

        $token = $this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }

        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('reset_password_error', sprintf(
                '%s - %s',
                ResetPasswordExceptionInterface::MESSAGE_PROBLEM_VALIDATE,
                [],
                'ResetPasswordBundle',
                $e->getReason(),
                [],
                'ResetPasswordBundle'
            ));

            return $this->redirectToRoute('app_forgot_password_request');
        }

        // The token is valid; allow the user to change their password.
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // A password reset token should be used only once, remove it.
            $this->resetPasswordHelper->removeResetRequest($token);

            // Encode(hash) the plain password, and set it.
            $encodedPassword = $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $this->entityManager->flush();

            // The session is cleaned up after the password has been changed.
            $this->cleanSessionAfterReset();

            return $this->redirectToRoute('app_home', [

                'img' => $imgAndSlogan->getImg(),
                'slogan' => $imgAndSlogan->getSlogan(),
                'user' => $user
            ]);
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),

            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan()
        ]);
    }

    private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer): RedirectResponse
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $emailFormData,
        ]);

        // Do not reveal whether a user account was found or not.
        if (!$user) {
            return $this->redirectToRoute('app_check_email');
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            // If you want to tell the user why a reset email was not sent, uncomment
            // the lines below and change the redirect to 'app_forgot_password_request'.
            // Caution: This may reveal if a user is registered or not.
            //
            // $this->addFlash('reset_password_error', sprintf(
            //     '%s - %s',
            //     $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_HANDLE, [], 'ResetPasswordBundle'),
            //     $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
            // ));

            return $this->redirectToRoute('app_check_email');
        }

        $email = (new TemplatedEmail())
            ->from(new Address('webmaster@waldganger.net', 'Waldganger'))
            ->to($user->getEmail())
            ->subject('Your password reset request')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
            ]);

        $mailer->send($email);

        // Store the token object in session for retrieval in check-email route.
        $this->setTokenObjectInSession($resetToken);

        return $this->redirectToRoute('app_check_email');
    }
    #[Route('/section', name: 'app_section_index', methods: ['GET'])]
    public function indexSection(SectionRepository $sectionRepository, imgAndSlogan $imgAndSlogan, UserInterface $user, Progression $progression): Response
    {

        return $this->render('section/index.html.twig', [
            'sections' => $sectionRepository->findAll(),
            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
            'formations' => $progression->getProgress()[0],
            'progress' => $progression->getProgress()[1],
            'progression' => $progression->getProgress()[2],
            'user' => $user
        ]);
    }

    #[Route('section/new', name: 'app_section_new', methods: ['GET', 'POST'])]
    public function newSection(imgAndSlogan $imgAndSlogan, Request $request, QuizzRepository $quizzRepository, FormationRepository $formationRepository, SectionRepository $sectionRepository, UserInterface $user): Response
    {

        $section = new Section();
        $this->getUser() ? $user = $this->getUser() : $user = new User();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->denyAccessUnlessGranted('ROLE_INSTRUCTEUR')) {
                $user->addSection($section);
                $sectionRepository->add($section);
            } else {
                $user->addSection($section);
                $sectionRepository->add($section);
            }
            $formation = $section->getId();
            return $this->render('section/index.html.twig', [
                'img' => $imgAndSlogan->getImg(),
                'slogan' => $imgAndSlogan->getSlogan(),
                'user' => $user,
                'section' => $section,
                'formation' => $formation
            ]);
        }
        return $this->renderForm('section/new.html.twig', [
            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
            'section' => $section,
            'formations' => $formationRepository->findAllFormationsOrderById($user->getId()),
            'form' => $form,
            'user' => $user
        ]);
    }

    #[Route('/section/{id}', name: 'app_section_show', methods: ['GET'])]
    public function showSection(Section $section, CoursRepository $coursRepository, imgAndSlogan $imgAndSlogan,Progression $progression, FormationRepository $formationRepository, Request $request): Response
    {
        $this->getUser() ? $user = $this->getUser() : $user = new User();
        return $this->render('section/show.html.twig', [
            'section' =>  $section,
            'formations' => $progression->getProgress()[0],
            'progress' => $progression->getProgress()[1],
            'progression' => $progression->getProgress()[2],
            'cours' => $coursRepository->findBy(['section' => $section]),
            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
            'user' => $user
        ]);
    }

    #[Route('/section/{id}/edit', name: 'app_section_edit', methods: ['GET', 'POST'])]
    public function editSection(Request $request, Section $section, UserInterface $user, SectionRepository $sectionRepository, imgAndSlogan $imgAndSlogan): Response
    {
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sectionRepository->add($section);
            return $this->redirectToRoute('app_section_index', [

                'img' => $imgAndSlogan->getImg(),
                'slogan' => $imgAndSlogan->getSlogan(),
                'user' => $user
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('section/edit.html.twig', [
            'section' => $section,

            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
            'user' => $user,
            'form' => $form
        ]);
    }

    #[Route('/section/{id}', name: 'app_section_delete', methods: ['POST'])]
    public function deleteSection(Request $request, Section $section, UserInterface $user, SectionRepository $sectionRepository, imgAndSlogan $imgAndSlogan): Response
    {
        if ($this->isCsrfTokenValid('delete' . $section->getId(), $request->request->get('_token'))) {
            $sectionRepository->remove($section);
        }

        return $this->redirectToRoute('app_section_index', [

            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
            'user' => $user
        ], Response::HTTP_SEE_OTHER);
    }

    #[Route('/quizz', name: 'app_quizz_index', methods: ['GET'])]
    public function quizzIndex(QuizzRepository $quizzRepository, imgAndSlogan $imgAndSlogan): Response
    {
        return $this->render('quizz/index.html.twig', [
            'quizzs' => $quizzRepository->findAll(),
            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
        ]);
    }

    #[Route('/quizz/new', name: 'app_quizz_new', methods: ['GET', 'POST'])]
    public function newQuizz(Request $request, UserInterface $user, SectionRepository $sectionRepository, imgAndSlogan $imgAndSlogan, QuizzRepository $quizzRepository): Response
    {
        $quizz = new Quizz();
        $form = $this->createForm(QuizzType::class, $quizz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quizzRepository->add($quizz);
            return $this->render('section/index.html.twig', [
                'img' => $imgAndSlogan->getImg(),
                'slogan' => $imgAndSlogan->getSlogan(),
                'user' => $user,
                'formations' => $user->getFormations(),
                'sections' => $sectionRepository->findBy(['user' => $user]),
                'section' => $quizzRepository->findOneByQuizzId($quizz->getId()),

            ]);
        }

        return $this->renderForm('quizz/new.html.twig', [
            'quizz' => $quizz,
            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
            'form' => $form,
            'user' => $user,
            'formations' => $user->getFormations(),
            'sections' => $user->getSections(),
        ]);
    }

    #[Route('/quizz/{id}', name: 'app_quizz_show', methods: ['GET'])]
    public function showQuizz(Quizz $quizz, imgAndSlogan $imgAndSlogan, SectionRepository $sectionRepository): Response
    {
        $this->getUser() ? $user = $this->getUser() : $user = new User();

        return $this->render('quizz/show.html.twig', [
            'quizz' => $quizz,
            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
            'sections' => $user->getSections(),
        ]);
    }

    #[Route('/quizz/{id}/edit', name: 'app_quizz_edit', methods: ['GET', 'POST'])]
    public function quizzEdit(Request $request, Quizz $quizz, QuizzRepository $quizzRepository,SectionRepository $sectionRepository, imgAndSlogan $imgAndSlogan): Response
    {
        $this->getUser() ? $user = $this->getUser() : $user = new User();

        $form = $this->createForm(QuizzType::class, $quizz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quizzRepository->add($quizz);
            return $this->redirectToRoute('app_quizz_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quizz/edit.html.twig', [
            'quizz' => $quizz,
            'form' => $form,
            'sections' => $user->getSections(),
            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
        ]);
    }

    #[Route('/quizz/{id}', name: 'app_quizz_delete', methods: ['POST'])]
    public function quizzDelete(Request $request, Quizz $quizz, QuizzRepository $quizzRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $quizz->getId(), $request->request->get('_token'))) {
            $quizzRepository->remove($quizz);
        }

        return $this->redirectToRoute('app_quizz_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, imgAndSlogan $imgAndSlogan): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 'error' =>
            $error,
            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan()
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route('/user', name: 'app_user_index', methods: ['GET'])]
    public function indexUser(UserRepository $userRepository, imgAndSlogan $imgAndSlogan): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),

            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan()
        ]);
    }


    #[Route('/user/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user, imgAndSlogan $imgAndSlogan): Response
    {
        return $this->render('user/show.html.twig', [
            'user' =>
            $user,

            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan()
        ]);
    }

    #[Route('/user/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository, imgAndSlogan $imgAndSlogan): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user);
            return $this->redirectToRoute('app_user_index', [

                'img' => $imgAndSlogan->getImg(),
                'slogan' => $imgAndSlogan->getSlogan()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' =>
            $form,

            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan()
        ]);
    }

    #[Route('/user/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository, imgAndSlogan $imgAndSlogan): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }

        return $this->redirectToRoute('app_user_index', [

            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan()
        ], Response::HTTP_SEE_OTHER);
    }
    #[Route('/progress', name: 'app_progress_index', methods: ['GET'])]
    public function indexProgress(ProgressRepository $progressRepository, imgAndSlogan $imgAndSlogan): Response
    {
        return $this->render('progress/index.html.twig', [
            'progress' => $progressRepository->findAll(),
            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
        ]);
    }

    #[Route('/progress/new/{id}', name: 'app_progress_new', methods: ['GET', 'POST'])]
    public function newProgress(Request $request, ImgAndSlogan $imgAndSlogan, FormationRepository $formationRepository, ProgressRepository $progressRepository, Int $id): Response
    {
        $this->getUser() ? $user = $this->getUser() : $user = new User();
        $formation = $formationRepository->find($id);
        $progress = new Progress();
        $progress->setFormation($formation);
        $progress->setUser($user);
        $user->addProgress($progress);
        $progressRepository->add($progress);
        $form = $this->createForm(ProgressType::class, $progress);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('app_progress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('progress/new.html.twig', [
            'progress' => $progress,
            'form' => $form,
            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
        ]);
    }

    #[Route('/progress/{id}', name: 'app_progress_show', methods: ['GET'])]
    public function showProgress(Progress $progress, imgAndSlogan $imgAndSlogan): Response
    {
        return $this->render('progress/show.html.twig', [
            'progress' => $progress,
            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
        ]);
    }

    #[Route('/progress/{id}/edit', name: 'app_progress_edit', methods: ['GET', 'POST'])]
    public function editProgress(Request $request, imgAndSlogan $imgAndSlogan, Progress $progress, ProgressRepository $progressRepository): Response
    {
        $form = $this->createForm(ProgressType::class, $progress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $progressRepository->add($progress);
            return $this->redirectToRoute('app_progress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('progress/edit.html.twig', [
            'progress' => $progress,
            'form' => $form,
            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
        ]);
    }
}
