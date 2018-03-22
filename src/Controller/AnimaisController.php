<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\AnimalType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AnimaisController extends Controller
{
    /**
     * @Route("/animais", name="listar_animais")
     * @Template("animais/index.html.twig")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $animais = $em->getRepository(Animal::class)->findAll();

        return [
            'animais' => $animais
        ];
    }

    /**
     * @Route("/animais/visualizar{id}", name="visualizar_animais")
     * @Template("animais/view.html.twig")
     * @param Animal $animal
     * @return array
     */
    public function view(Animal $animal)
    {
        return [
            'animal' => $animal
        ];
    }

    /**
     * @Route("animais/cadastrar", name="cadastrar_animais")
     * @Template("animais/create.html.twig")
     * @param Request $request
     */
    public function create(Request $request)
    {
        $animal = new Animal();
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($animal);
            $em->flush();

            $this->addFlash('success', "Animal foi salvo com sucesso!");
            return $this->redirectToRoute('listar_animais');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @return Response
     * @Template("animais/update.html.twig")
     * @Route("animais/editar/{id}", name="editar_animais")
     */
    public function update(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $animal = $em->getRepository(Animal::class)->find($id);
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($animal);
            $em->flush();
            $this->get("session")->getFlashBag()->set("success", "O animal " . $animal->getNome() . " foi alterado com sucesso!");
            return $this->redirectToRoute("listar_animais");
        }
        return [
            'animal' => $animal,
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("animais/apagar/{id}", name="apagar_animais")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $animal = $em->getRepository(Animal::class)->find($id);
        if (!$animal) {
            $mensagem = "Animal não foi encontrado!";
            $tipo = "warning";
        } else {
            $em->remove($animal);
            $em->flush();
            $mensagem = "Animal foi excluído com sucesso!";
            $tipo = "success";
        }
        $this->get('session')->getFlashBag()->set($tipo, $mensagem);
        return $this->redirectToRoute("listar_animais");
    }
}
