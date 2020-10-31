<?php

namespace App\Controller;
use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */

    private $repository;

  

    public function __construct(PropertyRepository $repository, ManagerRegistry $managerRegistry)
    {
        $this->repository = $repository;
        $this->managerRegistry = $managerRegistry;

    }

    /**
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     */

    public function show(Property $property, string $slug): Response
    {
        if ($property->getSlug() !== $slug) {
            return $this->redirectToRoute('property.show',[
                'id' =>$property->getId(),
                'slug'=>$property->getSlug()
            ], 301);
        }

        return $this->render('property/show.html.twig', [
            'property' => $property,
            'current_menu' => 'properties',
        ]);
    }

     /**
     * @Route("/biens", name="property.index")
     * @ Response
     */

    public function index(PaginatorInterface $paginator, Request $request): Response
    {
      
        $properties = $paginator-> paginate(
            $this -> repository-> findAllVisibleQuery(),
            $request-> query->getInt('page',1),
            12
        );

        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties',
            'properties' => $properties,
        ]);
    }
    
}