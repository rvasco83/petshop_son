<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class UsuariosController extends Controller
{
    /**
     * @Route("/usuarios", name="listar_usuarios")
     * @Template("/usuarios/index.html.twig")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $usuarios = $em->getRepository(Usuario::class)->findAll();

        return [
            'usuarios' => $usuarios
        ];
    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     * @Route("/usuarios/visualizar{id}", name="visualizar_usuarios")
     * @Template("usuarios/view.html.twig")
     */
    public function view(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository(Usuario::class)->find($id);

        return [
            'usuario' => $usuario
        ];
    }

    /**
     * @param Request $request
     * @Route("usuarios/cadastrar", name="cadastrar_usuarios")
     * @Template("usuarios/create.html.twig")
     * @return Response
     */
    public function create(Request $request)
    {
        $usuario = new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encoder = $this->get('security.password_encoder');
            $pass = $encoder->encodePassword($usuario, $usuario->getPassword());
            $usuario->setPassword($pass);
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush();

            $this->addFlash('success', "Usuario foi salvo com sucesso!");
            return $this->redirectToRoute('listar_usuarios');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @return Response
     * @Template("usuarios/update.html.twig")
     * @Route("usuarios/editar/{id}", name="editar_usuarios")
     */
    public function update(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository(Usuario::class)->find($id);
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encoder = $this->get('security.password_encoder');
            $pass = $encoder->encodePassword($usuario, $usuario->getPassword());
            $usuario->setPassword($pass);
            $em->persist($usuario);
            $em->flush();
            $this->get("session")->getFlashBag()->set("success", "O usuário " . $usuario->getNome() . " foi alterado com sucesso!");
            return $this->redirectToRoute("listar_usuarios");
        }
        return [
            'usuario' => $usuario,
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("usuarios/apagar/{id}", name="apagar_usuarios")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository(Usuario::class)->find($id);
        if (!$usuario) {
            $mensagem = "Usuario não foi encontrado!";
            $tipo = "warning";
        } else {
            $em->remove($usuario);
            $em->flush();
            $mensagem = "Usuario foi excluído com sucesso!";
            $tipo = "success";
        }
        $this->get('session')->getFlashBag()->set($tipo, $mensagem);
        return $this->redirectToRoute("listar_usuarios");
    }
}
