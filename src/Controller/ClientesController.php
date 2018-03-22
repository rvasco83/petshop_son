<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Form\ClienteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\VarDumper\VarDumper;

class ClientesController extends Controller
{
    /**
     * @Route("/clientes", name="listar_clientes")
     * @Template("clientes/index.html.twig")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $clientes = $em->getRepository(Cliente::class)->findAll();

        return [
            'clientes' => $clientes
        ];
    }

    /**
     * @Route("/clientes/visualizar{id}", name="visualizar_clientes")
     * @Template("clientes/view.html.twig")
     * @param Cliente $cliente
     * @return array
     */
    public function view(Cliente $cliente)
    {
        return [
            'cliente' => $cliente
        ];
    }

    /**
     * @Route("clientes/cadastrar", name="cadastrar_clientes")
     * @Template("clientes/create.html.twig")
     */
    public function create(Request $request)
    {
        $cliente = new Cliente();
        $form = $this->createForm(ClienteType::class, $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cliente);
            $em->flush();

            $this->addFlash('success', "Cliente foi salvo com sucesso!");
            return $this->redirectToRoute('listar_clientes');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @return Response
     * @Template("clientes/update.html.twig")
     * @Route("clientes/editar/{id}", name="editar_clientes")
     */
    public function update(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $cliente = $em->getRepository(Cliente::class)->find($id);
        $form = $this->createForm(ClienteType::class, $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($cliente);
            $em->flush();
            $this->get("session")->getFlashBag()->set("success", "O cliente " . $cliente->getNome() . " foi alterado com sucesso!");
            return $this->redirectToRoute("listar_clientes");
        }
        return [
            'cliente' => $cliente,
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @Route("clientes/apagar/{id}", name="apagar_clientes")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $cliente = $em->getRepository(Cliente::class)->find($id);
        if (!$cliente) {
            $mensagem = "Cliente não foi encontrado!";
            $tipo = "warning";
        } else {
            $em->remove($cliente);
            $em->flush();
            $mensagem = "Cliente foi excluído com sucesso!";
            $tipo = "success";
        }
        $this->get('session')->getFlashBag()->set($tipo, $mensagem);
        return $this->redirectToRoute("listar_clientes");
    }
}
