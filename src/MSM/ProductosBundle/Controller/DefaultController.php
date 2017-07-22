<?php

namespace MSM\ProductosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ProductosBundle:Default:index.html.twig');
    }

    public function registroAction(Request $request)
    {
    	$registro = $this->getDoctrine()
			->getRepository('ProductosBundle:Registro')
			->findAll();

		if(!$registro)
		{
			return $this->render('ProductosBundle:Default:default-productos.html.twig', array('mensaje' => 'No se ha realizado ninguna venta :('));
		}

		$costoTotal = 0;

		foreach ($registro as $producto) {
			$costoTotal += $producto->getCosto();
		}

		//obtenemos el paginador
		$paginator  = $this->get('knp_paginator');
    	$pagination = $paginator->paginate(
        $registro, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        15/*limit per page*/
    	);

		return $this->render('ProductosBundle:Default:registro.html.twig', array('pagination' => $pagination, 'costoTotal' => $costoTotal));
    }

    public function totalRegistroAction()
    {
        $registro = $this->getDoctrine()
            ->getRepository('ProductosBundle:Registro')
            ->findAll();

        $costoTotal = 0;

        foreach ($registro as $producto) {
            $costoTotal += $producto->getCosto();
        }

        return $this->render('ProductosBundle:Default:registrototal.html.twig', array('registro' => $registro, 'dinero' => $costoTotal));
    }  

    public function registroEliminarAction($id)
    {
        //mensaje para usuarios sin autorizaion
        if(!$this->get('security.context')->isGranted('ROLE_ADMIN')){
            return $this->render('ProductosBundle:Default:no-admin.html.twig');
        }
        //entity managaer para remover
        $em = $this->getDoctrine()->getManager();

        $registro = $this->getDoctrine()
            ->getRepository('ProductosBundle:Registro')
            ->find($id);

        if(!$registro){
            return new Response(
                json_encode(array('mensaje' => 'Error interno :C')),
                500,
                array('Content-Type' => 'application/json')
            );
        }else{
            $em->remove($registro);
            $em->flush();
        }

            return new Response(
                json_encode(array('mensaje' => 'Venta eliminada con exito')),
                200,
                array('Content-Type' => 'application/json')
            );
    }

    public function borrarAction(Request $request)
    {
        //mensaje para usuarios sin autorizaion
        if(!$this->get('security.context')->isGranted('ROLE_ADMIN')){
            return $this->render('ProductosBundle:Default:no-admin.html.twig');
        }

    	$em = $this->getDoctrine()->getManager();

    	$productos = $this->getDoctrine()
    		->getRepository('ProductosBundle:Registro')
    		->findAll();

    	if($request->get('borrar'))
    	{
    		foreach ($productos as $producto) {
    			$em->remove($producto);
    		}

    		$em->flush();

			return new Response(
				json_encode(array('mensaje' => 'El registro se ha eliminado :D')),
				200,
				array('Content-Type' => 'application/json')
			);	
    	}else{
			return new Response(
				json_encode(array('mensaje' => 'Ooops! ha ocurrido un error :(')),
				500,
				array('Content-Type' => 'application/json')
			);	
    	}	
    }

    public function aumentarAction(Request $request)
    {
        //mensaje para usuarios sin autorizaion
        if(!$this->get('security.context')->isGranted('ROLE_ADMIN')){
            return $this->render('ProductosBundle:Default:no-admin.html.twig');
        }

        //mensaje para usuarios sin autorizaion
        if(!$this->get('security.context')->isGranted('ROLE_ADMIN')){
            return $this->render('ProductosBundle:Default:no-admin.html.twig');
        }

        if($request->isXmlHttpRequest())
        {
            $dt = $this->getDoctrine();
            $em = $dt->getManager();

            $porcentaje = $request->get('porcentaje')/100;

            $motos = $dt->getRepository('ProductosBundle:Rmotos')
                ->findAll();

            $cauchos = $dt->getRepository('ProductosBundle:Cauchos')
                ->findAll();

            $lubricantes = $dt->getRepository('ProductosBundle:Lubricantes')
                ->findAll();

            if(!$motos || !$cauchos || !$lubricantes){
                return new Response(
                    json_encode(array('mensaje' => 'Opps algun error hace que no se pueda completar esta accion!')),
                    200,
                    array('Content-Type' => 'application/json')
                );         
            }

            foreach ($motos as $producto ) {
                $precio = $producto->getCosto();
                $producto->setCosto($precio+($precio*$porcentaje));     
            }

            foreach ($cauchos as $producto ) {
                $precio = $producto->getCosto();
                $producto->setCosto($precio+($precio*$porcentaje));     
            }

            foreach ($lubricantes as $producto ) {
                $precio = $producto->getCosto();
                $producto->setCosto($precio+($precio*$porcentaje));     
            }

            $em->flush();

            return new Response(
                json_encode(array('mensaje' => 'Aumento realizado')),
                200,
                array('Content-Type' => 'application/json')
            );
        }

        return $this->render('ProductosBundle:Default:aumentar-precios.html.twig');
    }

    public function informacionAction()
    {
        //mensaje para usuarios sin autorizaion
        if(!$this->get('security.context')->isGranted('ROLE_ADMIN')){
            return $this->render('ProductosBundle:Default:no-admin.html.twig');
        }
           
        $dt = $this->getDoctrine();
        //obtenemos todas las entidades para obtener datos
        $motos = $dt->getRepository('ProductosBundle:Rmotos')
            ->findAll();

        $cauchos = $dt->getRepository('ProductosBundle:Cauchos')
            ->findAll();

        $lubricantes = $dt->getRepository('ProductosBundle:Lubricantes')
            ->findAll();

        //variable a guardar todos los productos
        $totalProductos = 0;
        //variable para guardar el dinero total en productos
        $dineroTotal = 0;

        foreach ($motos as $producto) {
            $totalProductos += $producto->getCantidad();
            $dineroTotal += $producto->getCosto() * $producto->getCantidad();
        }

        foreach ($cauchos as $producto) {
            $totalProductos += $producto->getCantidad();
            $dineroTotal += $producto->getCosto() * $producto->getCantidad();
        }

        foreach ($lubricantes as $producto) {
            $totalProductos += $producto->getCantidad();
            $dineroTotal += $producto->getCosto() * $producto->getCantidad();
        }

        return $this->render('ProductosBundle:Default:informacion.html.twig', 
            array('totalProductos' => $totalProductos, 'dineroTotal' => $dineroTotal));
    }

        /*
    *Buscador global
    */

    public function buscadorGlobalAction($producto)
    {
      //mensaje para usuarios sin autorizaion
      if(!$this->get('security.context')->isGranted('ROLE_ADMIN') && !$this->get('security.context')->isGranted('ROLE_USER')){
          return $this->render('ProductosBundle:Default:no-admin.html.twig');
      }

      //entity manager
  		$em = $this->getDoctrine()->getManager();

      //consultamos en motos
			$consultaMotos = $em->createQuery("SELECT u FROM ProductosBundle:Rmotos u WHERE u.producto LIKE :producto OR u.id = :id");
			$consultaMotos->setParameter('producto','%'.$producto.'%');
			$consultaMotos->setParameter('id', $producto);
			//obtenemosproductos
			$listaMotos = $consultaMotos->getResult();

      //consultamos en cauchos
			$consultaCauchos = $em->createQuery("SELECT u FROM ProductosBundle:Cauchos u WHERE u.producto LIKE :producto OR u.id = :id");
			$consultaCauchos->setParameter('producto','%'.$producto.'%');
			$consultaCauchos->setParameter('id', $producto);
			//obtenemosproductos
			$listaCauchos = $consultaCauchos->getResult();

      //consultamos en lubricantes
			$consultaLubricantes = $em->createQuery("SELECT u FROM ProductosBundle:Lubricantes u WHERE u.aceite LIKE :producto OR u.id = :id");
			$consultaLubricantes->setParameter('producto','%'.$producto.'%');
			$consultaLubricantes->setParameter('id', $producto);
			//obtenemosproductos
			$listaLubricantes = $consultaLubricantes->getResult();

      //si no se encuentra ningun producto
      if(!$listaMotos && !$listaCauchos && !$listaLubricantes){
        return $this->render('ProductosBundle:Default:buscadornoproductos.html.twig');
      }

      return $this->render('ProductosBundle:Default:buscadorproductos.html.twig',
        array('motos' => $listaMotos, 'cauchos' => $listaCauchos, 'lubricantes' => $listaLubricantes));

    }

    public function inventarioAction()
    {
      //entity manager
      $em = $this->getDoctrine()->getManager();
      //mensaje para usuarios sin autorizaion
      if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')){
              return $this->render('ProductosBundle:Default:no-admin.html.twig');
      }

      $motos = $this->getDoctrine()
        ->getRepository('ProductosBundle:Rmotos')
        ->findAll();

      $cauchos = $this->getDoctrine()
        ->getRepository('ProductosBundle:Cauchos')
        ->findAll();

      $lubricantes = $this->getDoctrine()
        ->getRepository('ProductosBundle:Lubricantes')
        ->findAll();


    return $this->render('ProductosBundle:Default:inventario.html.twig', array('motos' => $motos,
      'cauchos' => $cauchos, 'lubricantes' => $lubricantes));
  }

  public function registrarClienteVentaAction(Request $request, $id)
  {
      //entitgy manager
      $em = $this->getDoctrine()->getManager();

      $registro = $this->getDoctrine()
          ->getRepository('ProductosBundle:Registro')
          ->find($id);

      //get inputs
      $nombre = $request->get('nombre');
      $cedula = $request->get('cedula');
      $tipo_pago = $request->get('pago');

      $registro->setNombre($nombre);
      $registro->setCedula($cedula);
      $registro->setTipoPago($tipo_pago);
      $em->flush();
      //obtenemos el producto para redireccionar a donde estaba
      $producto = $registro->getProducto();
      //verificamos de donde esta accediendo
      $tipo = $request->get('tipo');
      if($tipo == 'moto'){
        return $this->redirectToRoute('pruductos_motos_lista');
      }else if($tipo == 'caucho'){
        return $this->redirectToRoute('pruductos_cauchos_lista');
      }else if($tipo == 'lubricante'){
        return $this->redirectToRoute('pruductos_lubricantes_lista');
      }else{
        return $this->redirectToRoute('buscador_global', array('producto' => $producto));
      }
  }

}