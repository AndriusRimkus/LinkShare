<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Link;
use AppBundle\Entity\Category;
use AppBundle\Form\Type\LinkType;
use AppBundle\Form\Type\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery(
            'SELECT l FROM AppBundle:Link l
             ORDER BY l.publishedAt DESC'
        );

        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1)/*page number*/,
            3/*limit per page*/
        );

        return $this->render('AppBundle/index.html.twig', array('pagination' => $pagination));
    }

    /**
     * @Route("/addlink", name="addLink")
     */
    public function addLinkAction(Request $request) {
        $link = new Link();
        $user = $this->getUser();
        $link->setUser($user);

        $form = $this->createForm(new LinkType(), $link);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($link);
            $em->flush();

            $this->addFlash(
                'notice',
                'Your link was added!'
            );

            return new RedirectResponse($this->generateUrl('addLink'));
        }

        return $this->render('AppBundle/addlink.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/addcategory", name="addCategory")
     */
    public function addCategoryAction(Request $request) {
        $category = new Category();

        $form = $this->createForm(new CategoryType(), $category);
        $form->handleRequest($request);

        $categories = $this->getDoctrine()
            ->getRepository('AppBundle:Category')
            ->findAll();

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash(
                'notice',
                'Category was added!'
            );

            return new RedirectResponse($this->generateUrl('addCategory'));
        }

        return $this->render('AppBundle/addcategory.html.twig', array(
            'form' => $form->createView(),
            'categories' => $categories
        ));
    }

    private function getErrorMessages(\Symfony\Component\Form\Form $form) {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }

    /**
     * @Route("/removelink/{id}", requirements={"id" = "\d+"}, name="removeLink")
     */
    public function removeLinkAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $link = $em->getRepository('AppBundle:Link')->find($id);

        if (!$link) {
            throw $this->createNotFoundException('Unable to find specified link.');
        }

        if (!$link->isAuthor($this->getUser())) {
            throw $this->createNotFoundException('You don\'t have the rights to delete this link');
        }

        $em->remove($link);
        $em->flush();

        $this->addFlash(
            'notice',
            'Your link was removed!'
        );

        return new RedirectResponse($this->generateUrl('myLinks'));
    }

    /**
     * @Route("/mylinks", name="myLinks")
     */
    public function myLinksAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $query = $em->createQuery(
            'SELECT l FROM AppBundle:Link l
             WHERE l.user = :usr
             ORDER BY l.publishedAt DESC'
        )->setParameter('usr', $user);

        //$user->getId()
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1)/*page number*/,
            3/*limit per page*/
        );

        return $this->render('AppBundle/userlinks.html.twig', array('pagination' => $pagination));
    }
}
