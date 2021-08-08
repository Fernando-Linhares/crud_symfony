<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Products;
use \DateTimeImmutable;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main", methods={"GET"})
     */
    public function index(): Response
    {
        $products = $this->getDoctrine()
        ->getRepository(Products::class)
        ->findAll();
        return $this->render('main/index.html.twig',
            ['products' => $products]
        );
    }
 
    /**
     * @Route("/form", name="form", methods={"GET"})
     */
    public function form(): Response
    {
        return $this->render('main/form.html.twig');
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $product = $this->getDoctrine()
        ->getRepository(Products::class)
        ->find($id);
        return $this->render('main/form-edit.html.twig',
            ['product' => $product]
        );
    }

    /**
     * @Route("/store", name="store", methods={"POST"})
     */
    public function store(Request $request): Response
    {
        $data_form = $request->request->all();
        $product = new Products;
        $product->setName($data_form['name']);
        $product->setPrice($data_form['price']);
        $product->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimezone('America/Sao_Paulo')));
        $product->setCreatedAt(new \DateTimeImmutable('now', new \DateTimezone('America/Sao_Paulo')));
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($product);
        $manager->flush();
        return $this->redirectToRoute('main');  
    }

    /**
     * @Route("/update/{id}", name="update", methods={"PUT","POST","PATCH"})
     */
    public function update(int $id, Request $request): Response
    {
        $data_form = $request->request->all();
        $product = $this->getDoctrine()
        ->getRepository(Products::class)
        ->find($id);
        $product->setName($data_form['name']);
        $product->setPrice($data_form['price']);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($product);
        $manager->flush();
        return $this->redirectToRoute('main'); 
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE", "POST"})
     */
    public function delete($id): Response
    {
        $product = $this->getDoctrine()->getRepository(Products::class)->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($product);
        $manager->flush();
        return $this->redirectToRoute('main');
    }
}
