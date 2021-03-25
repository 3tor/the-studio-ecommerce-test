<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;

/**
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/{id}", name="profile")
     */
    public function index(User $user): Response
    {
        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="profile_edit")
     */
    public function edit(Request $request, User $user): Response
    {
        // $form = $this->createForm(RegistrationFormType::class, $user);

        $form = $this->createFormBuilder($user)
            ->add('email', EmailType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control mb-3')
            ))
            ->add('firstname', TextType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control mb-3')
            ))
            ->add('lastname', TextType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control mb-3')
            ))
            ->add('phone', TelType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control mb-3')
            ))
            ->add('address', TextType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control mb-3')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Update',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile', ['id' => $user->getId()]);
        }


        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
