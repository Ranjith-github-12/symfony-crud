<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PostController extends Controller
{
    /**
     * @Route ("/post",name="view_post_route")
     */
    
    public function viewPostAction()
    {
        $post=$this->getDoctrine()->getRepository('AppBundle:Post')->findAll();
        
        return $this->render("pages/index.html.twig",["post" => $post]);
    }
    
    /**
     * @Route ("/post/create",name="create_post_route")
     */
    
    public function createPostAction(Request $request)
    {
        $post= new Post;
        $form=$this->createFormbuilder($post)
        ->add('title',TextType::Class, array('attr' => array('class' => 'form-control')))
                 ->add('description',TextareaType::Class, array('attr' => array('class' => 'form-control')))
                 ->add('category',TextType::Class, array('attr' => array('class' => 'form-control')))
                ->add('save',SubmitType::Class, array('label'=>'Create Post','attr' => array('class' => 'form-control')))
                ->getForm();
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $title=$form['title']->getData();
            $description=$form['description']->getData();
            $category=$form['category']->getData();
            
            $post->setTitle($title);
            $post->setDescription($description);
            $post->setCategory($category);
            
            $em=$this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            
            $this->addFlash('message','Post Saved successfully');
            
            return $this->redirectToRoute('view_post_route');
            
        }
                
        return $this->render("pages/create.html.twig",['form' => $form->createView()]);
    }
    
    /**
     * @Route ("/post/update/{id}",name="update_post_route")
     */
    
    public function updatePostAction(Request $request,$id)
    {
        $post=$this->getDoctrine()->getRepository('AppBundle:Post')->find($id);
        
            $post->setTitle($post->getTitle());
            $post->setDescription($post->getDescription());
            $post->setCategory($post->getCategory());
            
             $form=$this->createFormbuilder($post)
        ->add('title',TextType::Class, array('attr' => array('class' => 'form-control')))
                 ->add('description',TextareaType::Class, array('attr' => array('class' => 'form-control')))
                 ->add('category',TextType::Class, array('attr' => array('class' => 'form-control')))
                ->add('save',SubmitType::Class, array('label'=>'Create Post','attr' => array('class' => 'form-control')))
                ->getForm();
             
              $form->handleRequest($request);
              
               if($form->isSubmitted() && $form->isValid())
        {
            $title=$form['title']->getData();
            $description=$form['description']->getData();
            $category=$form['category']->getData();
            
             $em=$this->getDoctrine()->getManager();
          
             $post=$em->getRepository('AppBundle:Post')->find($id);
             
             $post->setTitle($title);
            $post->setDescription($description);
            $post->setCategory($category);
            
           
            $em->flush();
            
            $this->addFlash('message','Post Saved Updated');
            
            return $this->redirectToRoute('view_post_route');
            
        }
            
        return $this->render("pages/update.html.twig",['form' => $form->createView()]);
    }
    
    /**
     * @Route ("/post/show/{id}",name="show_post_route")
     */
    
    public function showPostAction($id)
    {
        $post=$this->getDoctrine()->getRepository('AppBundle:Post')->find($id);
        
        return $this->render("pages/show.html.twig",["post" => $post]);
    }
    
    /**
     * @Route ("/post/delete/{id}",name="delete_post_route")
     */
    
    public function deletePostAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        
        $post=$em->getRepository('AppBundle:Post')->find($id);
        
        $em->remove($post);
        
        $em->flush();
        
        $this->addFlash('message','Post deleted');
            
            return $this->redirectToRoute('view_post_route');
        
    }
}
