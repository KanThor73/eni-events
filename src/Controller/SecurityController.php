<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Entity\User;
use App\Repository\ResetPasswordRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->json([]);// throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/newPassword/{token}', name: 'new_password')]
    function newPasswordForReset(string $token, Request $request, UserPasswordHasherInterface $userPasswordHasher, ResetPasswordRepository $resetPasswordRepository, EntityManagerInterface $entityManager): response
    {

        $mdpsForm = $this->createFormBuilder()
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas etre vide'
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => '6 caracteres minimum',
                    ])
                ],
                'label' => 'Nouveau mot de passe'
            ])
            ->add('confirmPassword', PasswordType::class,
                [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Ce champ ne peut pas etre vide',
                        ]),
                    ],
                    'label' => 'Confirmation mot de passe'
                ])
            ->getForm();

        $user = new User();

        $resetPassword = $resetPasswordRepository->findOneBy(['token' => $token]);

        if (!$resetPassword || $resetPassword->getExpiredAt() < new \DateTime('now')) {

            if ($resetPassword) {
                $entityManager->remove($resetPassword);
                $entityManager->flush();
            }
            $this->addFlash('error', 'Votre lien a expire');
            return $this->redirectToRoute('app_login');
        }
        $mdpsForm->handleRequest($request);

        $newMdps = $mdpsForm->get('password')->getData();
        $confirmMdps = $mdpsForm->get('confirmPassword')->getData();

        if ($mdpsForm->isSubmitted() && $mdpsForm->isValid()) {
            if ($newMdps != $confirmMdps) {
                $this->addFlash('error', 'Les mots de passe doivent etre identiques');
                return $this->redirectToRoute('new_password', ['token' => $token]);
            }
            $user = $resetPassword->getUser();
            $hash = $userPasswordHasher->hashPassword($user, $newMdps);
            $user->setPassword($hash);

            $entityManager->remove($resetPassword);

            $entityManager->flush();
            $this->addFlash('success', 'Votre mot de passe a bien ete modifie');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/new_password.html.twig', [
            'form' => $mdpsForm->createView()
        ]);
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    #[Route(path: '/reset_password', name: 'reset_password')]
    public function resetPassword(MailerInterface $mailer, EntityManagerInterface $entityManager, Request $request, UserRepository $userRepository, ResetPasswordRepository $resetPasswordRepository): Response
    {
        $emailForm = $this->createFormBuilder()->add('email', EmailType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'le champ email ne peut pas etre vide'
                ])
            ]
        ])->getForm();

        $emailForm->handleRequest($request);

        if ($emailForm->isSubmitted() && $emailForm->isValid()) {

            $emailFromForm = $emailForm->get('email')->getData();
            $user = $userRepository->findOneBy(['email' => $emailFromForm]);

            if ($user) {
                $oldResetPassword = $resetPasswordRepository->findOneBy(['user' => $user]);

                if ($oldResetPassword) {
                    $entityManager->remove($oldResetPassword);
                    $entityManager->flush();
                }

                $resetPassword = new ResetPassword();

                $resetPassword->setUser($user);
                $resetPassword->setExpiredAt(new \DateTimeImmutable('+1 hours')); // le token est valide pour 1 heures

                $token = substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(30))), 0, 20);
                $resetPassword->setToken($token);

                $entityManager->persist($resetPassword);
                $entityManager->flush();

                $email = new TemplatedEmail();
                $email
                    ->to('bidule@eni.com')
                    ->subject('Reinitialisation de mot de passe')
                    ->htmlTemplate('emails/reset_password.html.twig')
                    ->context([
                        'username' => $user->getFirstname(),
                        'token' => $token
                    ]);

                $mailer->send($email);
            }
            $this->addFlash('success', 'Un email de reinitialisation viens de vous etre envoye');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'form' => $emailForm->createView()
        ]);
    }
}
