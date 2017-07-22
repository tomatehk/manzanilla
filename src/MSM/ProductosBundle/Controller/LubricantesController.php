<?php

namespace MSM\ProductosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
//entidad
use MSM\ProductosBundle\Entity\Lubricantes;
use MSM\ProductosBundle\Form\LubricantesType;
use Symfony\Component\HttpFoundation\Response;
//registro
use MSM\ProductosBundle\Entity\Registro;

class LubricantesController extends Controller
{	
	/*
	*Inicio Agregar nuevos productos
	*/
	public function agregarAction()
	{	
		//mensaje para usuarios sin autorizaion
		if(!$this->get('security.context')->isGranted('ROLE_ADMIN')){
            return $this->render('ProductosBundle:Default:no-admin.html.twig');
        }

		$producto = new Lubricantes();
		$em = $this->getDoctrine()->getManager();
		$form = $this->crearFormProductos($producto);

		return $this->render('ProductosBundle:Lubricantes:agregar-producto-lubricantes.html.twig', array('form' => $form->createView()));
	}

	public function agregarCheckAction(Request $request)
	{
		//mensaje para usuarios sin autorizaion
		if(!$this->get('security.context')->isGranted('ROLE_ADMIN')){
            return $this->render('ProductosBundle:Default:no-admin.html.twig');
        }

		$producto = new Lubricantes();
		$em = $this->getDoctrine()->getManager();
		$form = $this->crearFormProductos($producto);
		$form->handleRequest($request);

		if($form->isValid()){
			$em->persist($producto);
			$em->flush();
		}else{
			return $this->render('ProductosBundle:Lubricantes:agregar-producto-lubricantes.html.twig', array('form' => $form->createView()));
		}

		$this->addFlash(
			'noticia',
			'Se ha guardado el producto'
		);

		return $this->redirectToRoute('productos_lubricantes_agregar');
	}

	private function crearFormProductos(Lubricantes $entity)
	{
		$form = $this->createForm(new LubricantesType(), $entity, array(
			'action' => $this->generateUrl('productos_agregar_lubricantes_check'),
			'method' => 'POST'
		));

		$form->add('Agregar', 'submit');

		return $form;
	}

	/*
	*Fin Agregar nuevos productos
	*/

	/*
	*Inicio lista productos
	*/
	public function listaAction(Request $request)
	{	
		//entity manager
		$em = $this->getDoctrine()->getManager();
		//mensaje para usuarios sin autorizaion
		if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')){
            return $this->render('ProductosBundle:Default:no-admin.html.twig');
        }

		//comprobamos si la variable de consulta esta llena
		if(!empty($request->get('producto'))){
			$producto = $request->get('producto');
			//consultamos
			$consulta = $em->createQuery("SELECT u FROM ProductosBundle:Lubricantes u WHERE u.aceite LIKE :producto OR u.id = :id");
			$consulta->setParameter('producto','%'.$producto.'%');
			$consulta->setParameter('id', $producto);
			//obtenemosproductos
			$listaProductos = $consulta->getResult();
		}else{
			$listaProductos = $this->getDoctrine()
				->getRepository('ProductosBundle:Lubricantes')
				->findAll();
		}

		if(!$listaProductos){
			return $this->render('ProductosBundle:Lubricantes:no-productos-lubricantes.html.twig', array('mensaje' => 'No hay productos de este tipo :( '));
		}

		//obtenemos el paginador
		$paginator  = $this->get('knp_paginator');
    	$pagination = $paginator->paginate(
        $listaProductos, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        10/*limit per page*/
    	);

		return $this->render('ProductosBundle:Lubricantes:lista-productos-prueba.html.twig', array('pagination' => $pagination));
	}
	/*
	*Fin lista productos
	*/

	/*
	*Inicio editar productos
	*/
	public function editarAction($id ,Request $request)
	{
		//entitgy manager
		$em = $this->getDoctrine()->getManager();
		//mensaje para usuarios sin autorizaion
		if(!$this->get('security.context')->isGranted('ROLE_ADMIN')){
            return $this->render('ProductosBundle:Default:no-admin.html.twig');
        }

		$producto = $this->getDoctrine()
			->getRepository('ProductosBundle:Lubricantes')
			->find($id);

		if(!$producto){
			throw $this->createNotFoundException(
				'No existen productos'
			);
		}

		$form = $this->crearFormProductosEditar($producto, $id);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()){
			$em->flush();

			$this->addFlash(
				'noticia',
				'Producto editado correctamente'
			);
		}

		return $this->render('ProductosBundle:Lubricantes:editar-producto-lubricantes.html.twig', array('form' => $form->createView()));
	}

	private function crearFormProductosEditar(Lubricantes $entity, $id)
	{
		$form = $this->createForm(new LubricantesType(), $entity, array(
			'action' => $this->generateUrl('productos_lubricantes_editar', array('id' => $id)),
			'method' => 'POST'
		));

		$form->add('Agregar', 'submit');

		return $form;
	}

	/*
	*Fin lista productos
	*/

	/*
	*Inicio compra productos
	*/
	public function compraAction(Request $request, $id)
	{
		//mensaje para usuarios sin autorizaion
		if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')){
            return $this->render('ProductosBundle:Default:no-admin.html.twig');
        }
        	
		$registro = new Registro();

		$em = $this->getDoctrine()->getManager();
		$producto = $this->getDoctrine()
			->getRepository('ProductosBundle:Lubricantes')
			->find($id);

		if(!$producto){
			throw $this->createNotFoundException(
				'Error al descontar el producto'
			);			
		}

		$cantidad = $request->get('cantidad');
		$productoCantidad = $producto->getCantidad();

		if($productoCantidad >= $cantidad){
			//si es valida la condicion tambien agregamos el producto al registro
			$registro->setProducto($producto->getAceite());
			$registro->setDescripcion($producto->getTipo());
			$registro->setCantidad($cantidad);
			$registro->setCosto($producto->getCosto() * $cantidad);
			//descontamos el producto
			$producto->setCantidad($productoCantidad-$cantidad);

			$em->persist($registro);
			$em->persist($producto);
			$em->flush();

			return new Response(
				json_encode(array('mensaje' => 'Compra realizada y agregada al registro',
					'id_registro' => $registro->getId())),
				200,
				array('Content-Type' => 'application/json')
			);	
		}else{
			return new Response(
				json_encode(array('mensaje' => 'Error no se puede realizar la compra por falta de productos')),
				500,
				array('Content-Type' => 'application/json')
			);				
		}
	}
	/*
	*Fin compra productos
	*/

	/*
	*Inicio eliminar productos
	*/
	public function eliminarAction($id)
	{
		//mensaje para usuarios sin autorizaion
		if(!$this->get('security.context')->isGranted('ROLE_ADMIN')){
            return $this->render('ProductosBundle:Default:no-admin.html.twig');
        }
		
		$em = $this->getDoctrine()->getManager();
		$producto = $this->getDoctrine()
			->getRepository('ProductosBundle:Lubricantes')
			->find($id);
			
		if(!$producto){
			return new Response(
				json_encode(array('mensaje' => 'Error no se puede eliminar el producto!')),
				500,
				array('Content-Type' => 'application/json')
			);
		}

		//eliminamos el producto 
		$em->remove($producto);
		$em->flush();

		return new Response(
			json_encode(array('mensaje' => 'Se ha eliminado el producto!')),
			200,
			array('Content-Type' => 'application/json')
		);

	}
	/*
	*Fin eliminar productos
	*/
}
