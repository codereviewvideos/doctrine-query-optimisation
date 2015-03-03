<?php

namespace AppBundle\Controller;

use Doctrine\ORM\Query;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function findAllVsJoinAction()
    {
        $em = $this->getDoctrine()->getManager();

//        $topics = $em->getRepository('AppBundle:Topic')->findAll();

        $qb = $em->createQueryBuilder('qb');

        $topics = $qb->select('t', 'r')
            ->from('AppBundle:Topic', 't')
            ->join('t.replies', 'r')
            ->getQuery()
            ->getResult()
        ;

        return $this->render('default/index.html.twig', array(
            'topics' => $topics
        ));
    }


    /**
     * @Route("/hydration", name="hydration")
     */
    public function hydrationAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder('qb');

        $topics = $qb->select('t', 'r')
            ->from('AppBundle:Topic', 't')
            ->join('t.replies', 'r')
            ->getQuery()
//            ->getResult()
//            ->getResult(Query::HYDRATE_OBJECT)
//            ->getResult(Query::HYDRATE_ARRAY)
            ->getArrayResult()
        ;

        dump($topics);

        return $this->render('default/index.html.twig', array(
            'topics' => $topics
        ));
    }
}
