<?php

namespace App\Controller;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPropertyController extends AbstractController
{



    public function __construct(PropertyRepository $repository, ManagerRegistry $managerRegistry)
    {
        $this->repository = $repository;
        $this->managerRegistry = $managerRegistry;
    }


    /**
     * @Route("/admin", name="admin.property.index")
     */

    public function index(): Response
    {
        $properties = $this->repository->findAll();
        return $this->render('admin_property/index.html.twig', compact('properties'));
    }

    /**
     * @Route("/admin/property/create", name="admin.property.new")
     */

    public function new(Request $request)
    {
        $property = new Property();
        $form =  $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($property);
            $manager->flush();
            $this->addFlash(
                'success',
                'la bien est bien modifié'
            );
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin_property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/property/{id}", name="admin.property.edit")
     */

    public function edit(PropertyRepository $propertyRepository, $id,  Request $request)
    {

        

        $property = $propertyRepository->find($id);

        $form =  $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager = $this->getDoctrine()->getManager();
            // on récupère l'objet
            $manager->persist($property);
            // on  renvoi l'objet
            $manager->flush();
            $this->addFlash(
                'success',
                'Bien est bien modifié'
            );            
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('admin_property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/property/delete/{id}", name="admin.property.delete")
     */

    public function delete(PropertyRepository $propertyRepository, $id)
    {
        $property = $propertyRepository->find($id); 

            $manager = $this->getDoctrine()->getManager();
            $manager->remove($property);
            $manager->flush();
            $this->addFlash(
                'success',
                'Bien est bien supprimé'
            ); 

            return $this->redirectToRoute('admin.property.index');
        
        
    }
     
    }
