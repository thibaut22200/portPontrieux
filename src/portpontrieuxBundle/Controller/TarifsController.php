<?php

namespace portpontrieuxBundle\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
 
class TarifsController extends Controller
{
    public function indexAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $rep = $manager->getRepository('portpontrieuxBundle:Emplacement');
        //$lesEmplacements = $rep->findAll();
        //$lesEmplacements = $rep->mesEmplacementsDQL($manager);
        $lesEmplacements = $rep->mesEmplacementsQueryBuilder();
        return $this->render('portpontrieuxBundle:Tarifs:index.html.twig', Array('lesEmplacements' => $lesEmplacements));
    }
	
	    public function listeAction($page) // Ce paramètre va contenir le numéro de la page à afficher
    {
        if ($page < 1)
              {
                     throw $this->createNotFoundException("La page ".$page." n'existe pas.");
              }
 
              // On peut fixer le nombre de lignes avec la ligne suivante :
              // $nbParPage = 5;
              // Mais bien sûr il est préférable de définir un paramètre dans "app\config\parameters.yml", et d'y accéder comme ceci :
              $nbParPage = $this->container->getParameter('nb_par_page');
             
              // On récupère l'objet Paginator
              $manager = $this->getDoctrine()->getManager();
              $rep = $manager->getRepository('portpontrieuxBundle:Emplacement');
              $lesEmplacements = $rep->mesEmplacementsQueryBuilder($page, $nbParPage);
             
              // On calcule le nombre total de pages grâce au count($lesEmplacements) qui retourne le nombre total d'emplacements
              $nbPages = ceil(count($lesEmplacements) / $nbParPage);
             
              // Si la page n'existe pas, on retourne une erreur 404
              if ($page > $nbPages)
              {
                     throw $this->createNotFoundException("La page ".$page." n'existe pas.");
              }
             
              // On donne toutes les informations nécessaires à la vue
              return $this->render('portpontrieuxBundle:Tarifs:index.html.twig', array(
                'lesEmplacements' => $lesEmplacements,
                'nbPages'     => $nbPages,
                'page'        => $page,
              ));
    }
}