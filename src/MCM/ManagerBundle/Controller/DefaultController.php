<?php

namespace MCM\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use MCM\ManagerBundle\Entity\Manager;
use MCM\ManagerBundle\Form\Type\ManagerType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('send@example.com')
            ->setTo('kkam@mac.com')
            ->setBody('You should see me from the profiler!')
        ;

        $this->get('mailer')->send($message);
    	return $this->render('MCMManagerBundle:Default:home.html.twig');
       //  return $this->render('MCMManagerBundle:Default:index.html.twig', array('name' => $name));
    }
    
    public function contactAction()
    {
        $id = 1;
        $person = $this->getDoctrine()
                ->getRepository('MCMManagerBundle:Person')
                ->find($id);
        
        $form = $this->createFormBuilder($person)
                ->add('name','text')
                ->add('id', 'integer')
                ->add('description','text')
                ->add('save','submit')
                ->getForm();
        
    	return $this->render('MCMManagerBundle:Person:Detail.html.twig', array('form' => $form->createView()));
       //  return $this->render('MCMManagerBundle:Default:index.html.twig', array('name' => $name));
    }
    
    public function contactlistAction()
    {
    return $this->render('MCMManagerBundle:Default:index.html.twig');
}
    
    public function userAction()
    {
        $username = 'username';
        $password = 'password';
        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository('MCMManagerBundle:User');
        
        $user = $repository->findOneBy(array('username'=>$username,'password'=>$password));
     
            return $this->render('MCMManagerBundle:Default:index.html.twig');
        
        
    }
    
    public function managerlistAction(Request $request)
    {
        $page = $request->get('page');
        $count_per_page = 50;
        $total_count = $this->getTotalManagers();
        $total_pages=ceil($total_count/$count_per_page);

        if(!is_numeric($page)){
            $page=1;
        }
        else{
            $page=floor($page);
        }
        if($total_count<=$count_per_page){
            $page=1;
        }
        if(($page*$count_per_page)>$total_count){
            $page=$total_pages;
        }
        $offset=0;
        if($page>1){
            $offset = $count_per_page * ($page-1);
        }
        $em = $this->getDoctrine()->getEntityManager();
        $ctryQuery = $em->createQueryBuilder()
                ->select('c')
                ->from('MCMManagerBundle:Manager', 'c')
                ->setFirstResult($offset)
                ->setMaxResults($count_per_page);
        $ctryFinalQuery = $ctryQuery->getQuery();

        $managers = $ctryFinalQuery->getArrayResult();
        return $this->render('MCMManagerBundle:Default:table.html.twig', array('managers'=>$managers,'total_pages'=>$total_pages,'current_page'=> $page));
    }
    
    public function managerAction($managerkey)
    {
           $manager = $this->getDoctrine()
            ->getRepository('MCMManagerBundle:Manager')
            ->find($managerkey);
        
        $form = $this->createForm(new ManagerType(),$manager);
        
//        $form = $this->createFormBuilder($managerkey)
//        ->add('login','text')
//        ->add('firstname', 'text')
//        ->add('lastname','text')
//        ->add('managerkey','text')
//        ->add('save','submit')
//        ->getForm();
        
    	return $this->render('MCMManagerBundle:Default:detail.html.twig', array('form' => $form->createView()));

    }
    
    
    
    
    public function getTotalManagers() {
        $em = $this->getDoctrine()->getEntityManager();
        $countQuery = $em->createQueryBuilder()
                ->select('Count(c)')
                ->from('MCMManagerBundle:Manager', 'c');
        $finalQuery = $countQuery->getQuery();
        $total = $finalQuery->getSingleScalarResult();
        return $total;
    }
}