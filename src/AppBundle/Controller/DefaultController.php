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


    /**
     * @Route("/lazy-collections", name="lazy_collections")
     */
    public function lazyCollectionsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $topic = $em->getRepository('AppBundle:Topic')->find(1);

        return $this->render('default/index.html.twig', array(
            'topic' => $topic
        ));
    }


    /**
     * @Route("/cache-result", name="cache_result")
     */
    public function cachingQueryResultAction()
    {
        $em = $this->getDoctrine()->getManager();

//        $topics = $em->getRepository('AppBundle:Topic')->findAll();

        $qb = $em->createQueryBuilder('qb');

        $topics = $qb->select('t', 'r')
            ->from('AppBundle:Topic', 't')
            ->join('t.replies', 'r')
            ->getQuery()
            ->useResultCache(true, 5, 'my_cache_id')
            ->getResult()
        ;

        return $this->render('default/index.html.twig', array(
            'topics' => $topics
        ));
    }


    /**
     * @Route("/using-references", name="using_references")
     */
    public function usingReferencesAction()
    {
        $em = $this->getDoctrine()->getManager();

//        $reply = $em->getRepository('AppBundle:Reply')->find(33);
        $reply = $em->getReference('AppBundle\Entity\Reply', 35);

        $topic = $em->getRepository('AppBundle:Topic')->find(1); /** @var $topic \AppBundle\Entity\Topic */

        $topic->addReply($reply);

        $em->persist($topic);
        $em->flush();

        return $this->render('default/index.html.twig', array(
            'topic' => $topic
        ));
    }


    /**
     * @Route("/property-subsets", name="property_subsets")
     */
    public function propertySubsetsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder('qb');

        $topics = $qb->select('partial t.{id,author}')
            ->from('AppBundle:Topic', 't')
            ->getQuery()
            ->getResult()
        ;

        dump($topics);

        return $this->render('default/index.html.twig', array(
            'topics' => $topics
        ));
    }


    /**
     * @Route("/db-index", name="db_index")
     */
    public function dbIndexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder('qb');

        $topics = $qb->select('r')
            ->from('AppBundle:Reply', 'r')
            ->where('r.message LIKE :message')
            ->setParameter('message', '%magnam%')
            ->getQuery()
            ->getResult()
        ;

        dump($topics);

        return $this->render('default/index.html.twig', array(
            'topics' => $topics
        ));
    }
}
