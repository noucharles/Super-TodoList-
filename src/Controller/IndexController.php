<?php
namespace App\Controller;

use App\Entity\Tache;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class IndexController extends AbstractController
{
      /**
     *@Route("/",name="tache_list")
     */
  public function home()
  {
    //récupérer tous les taches de la table tache de la BD
    // et les mettre dans le tableau $taches
    $taches= $this->getDoctrine()->getRepository(Tache::class)->findAll();
    return  $this->render('taches/index.html.twig',['taches' => $taches]);  
  }

   /**
      * @Route("/tache/save")
      */
     public function save() {
       $entityManager = $this->getDoctrine()->getManager();

       $tache = new Tache();
       $tache->setNom('Tache 3');
       $tache->setDescription("Le matin");
      
       $entityManager->persist($tache);
       $entityManager->flush();

       return new Response('Tache enregisté avec id   '.$tache->getId());
     }


    /**
     * @Route("/tache/new", name="new_tache")
     * Method({"GET", "POST"})
     */
    public function new(Request $request) {
        $tache = new Tache();
        $form = $this->createFormBuilder($tache)
          ->add('nom', TextType::class)
          ->add('description', TextType::class)
          ->add('save', SubmitType::class, array(
            'label' => 'Créer')
          )->getForm();
          
  
        $form->handleRequest($request);
  
        if($form->isSubmitted() && $form->isValid()) {
          $tache = $form->getData();
  
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($tache);
          $entityManager->flush();
  
          return $this->redirectToRoute('tache_list');
        }
        return $this->render('taches/new.html.twig',['form' => $form->createView()]);
    }

      

      /**
     * @Route("/tache/{id}", name="tache_show")
     */
    public function show($id) {
        $tache = $this->getDoctrine()->getRepository(Tache::class)->find($id);
  
        return $this->render('taches/show.html.twig', array('tache' => $tache));
      }


    /**
     * @Route("/tache/edit/{id}", name="edit_tache")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $id) {
        $tache = new Tache();
        $tache = $this->getDoctrine()->getRepository(Tache::class)->find($id);
  
        $form = $this->createFormBuilder($tache)
          ->add('nom', TextType::class)
          ->add('description', TextType::class)
          ->add('save', SubmitType::class, array(
            'label' => 'Modifier'         
          ))->getForm();
  
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
  
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->flush();
  
          return $this->redirectToRoute('tache_list');
        }
  
        return $this->render('taches/edit.html.twig', ['form' => $form->createView()]);
      }

   /**
     * @Route("/tache/delete/{id}",name="delete_tache")
     * @Method({"DELETE"})
     */
    public function delete(Request $request, $id) {
        $tache = $this->getDoctrine()->getRepository(Tache::class)->find($id);
  
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($tache);
        $entityManager->flush();
  
        $response = new Response();
        $response->send();

        return $this->redirectToRoute('tache_list');
      }
  
    /**
     * @Route("/tache/carriedOut/{id}",name="carriedOut_tache")
     * @Method({"POST"})
     */
    public function carriedOut(Request $request, $id) {
      $tache = $this->getDoctrine()->getRepository(Tache::class)->find($id);
      $tache->setIsFinished(true);

      
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->flush();

      return $this->redirectToRoute('tache_list');
    }


    /**
     *@Route("/isFinish",name="isFinish")
     */
  public function isFinish()
  {
    //récupérer tous les taches de la table tache de la BD
    // et les mettre dans le tableau $taches
    $taches= $this->getDoctrine()->getRepository(Tache::class)->findBy(
      ['isFinished' => 0]
  );
    return  $this->render('taches/isFinished.html.twig',['taches' => $taches]);  
  }
}

