<?php

namespace Moex\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Request\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Moex\CoreBundle\Entity\MeOrders;
use Moex\CoreBundle\Entity\MeOrdermeta;
use Moex\CoreBundle\Entity\MeOrderGoimon;
use Moex\CoreBundle\Form\MeOrdersType;
use Moex\CoreBundle\Form\OrderFilterType;
use Moex\CoreBundle\Entity\MeDrivers;
use Moex\CoreBundle\Form\MeDriversType;
use Moex\CoreBundle\Form\DriverFilterType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\View\RouteRedirectView,
    FOS\RestBundle\View\View,
    FOS\RestBundle\Controller\Annotations\QueryParam,
    FOS\RestBundle\Controller\Annotations\Hateoas,
    FOS\RestBundle\Request\ParamFetcherInterface;

/**
 * Api controller.
 *
 * @Route("/api")
 */
class ApiController extends Controller
{
    /**
     * @Route("/index", name="api")
     */
    public function indexAction()
    {
		return array();
    }
    /**
     * @Route("/embedded/create", name="api_embbed_create")
     */
    public function embeddedCreateAction()
	{
		//$request = $this->getRequest();
		$request = $this->get('request')->request->all();
		//var_dump($request['orderfrom']);die;
		$em = $this->getDoctrine()->getEntityManager();
		$order = new MeOrders();
		$order->setCustomerId(0);
		$order->setServiceType(0);
		$order->setLat('21.0267');
		$order->setLng('105.83659');
		$created_at = new \DateTime();
		$updated_at = new \DateTime();
		$user = $em->getRepository("MoexCoreBundle:MeUsers")->find(1);
		$order->setUser($user);
		$order->setCreatedAt($created_at);
		$order->setUpdatedAt($updated_at);
		$order->setOrderFrom($request['orderfrom']);
		$order->setOrderTo($request['orderto']);
		$order->setRoadPrice(str_replace(',','',$request['orderprice']));
		$order->setSenderAddress($request['orderemail']);
		$order->setPrice(str_replace(',','',$request['orderprice']));
		$order->setTotalPrice(str_replace(',','',$request['orderprice']));
		$order->setPhone($request['orderphone']);
		$order->setOrderInfo($request['orderurl']);
		$order->setOrderStatus($this->container->getParameter("moex.order.status.pending"));
		$order->setOrderName($request['ordername']);
		$em->persist($order);
		$em->flush();
		$response = new Response(json_encode($request));
		return $response;
	}
    /**
     * @Route("/goimon/create", name="api_goimon_create")
     */
    public function goimonCreateAction()
    {
		$request = $this->getRequest();
		$j = $this->getRequest()->query->get('order');
		$j = stripslashes($j);
    
		$j = str_replace("\n", "\\n", $j);
		$j = str_replace("\r", "\\r", $j);
		$arr_order = json_decode($j);

        $em = $this->getDoctrine()->getEntityManager();
		$order = new MeOrders();
		$order->setCustomerId(0);
		$order->setServiceType(0);
		$order->setLat('21.0267');
		$order->setLng('105.83659');
		$created_at = new \DateTime();
		$updated_at = new \DateTime();
		$user = $em->getRepository("MoexCoreBundle:MeUsers")->find(1);
		$order->setUser($user);
		$order->setCreatedAt($created_at);
		$order->setUpdatedAt($updated_at);
		$order->setOrderName($arr_order->TenNhaHang);
		$order->setPhone($arr_order->DTNhaHang);
		$order->setSenderAddress($arr_order->DCNhaHang);
		$order->setOrderFrom($arr_order->DCNhaHang);
		$order->setReceiverName($arr_order->NguoiNhan);
		$order->setReceiverPhone($arr_order->SDTNguoiNhan);
		$order->setReceiverAddress($arr_order->DCNguoiNhan);
		$order->setOrderTo($arr_order->DCNguoiNhan);
		$order->setRoadPrice(str_replace(',','',$arr_order->PhiGiaoHang));
		$order->setPrice(str_replace(',','',$arr_order->PhiGiaoHang));
		$order->setExtraPrice(str_replace(',','',$arr_order->TongThanhToan));
		$order->setTotalPrice(str_replace(',','',$arr_order->GiaTriDonHang));
		$order->setPromotion($arr_order->KhuyenMai);
		$order->setOrderInfo($arr_order->GhiChu);
		$order->setOrderStatus($this->container->getParameter("moex.order.status.pending"));
		
		$em->persist($order);
		$em->flush();
		$ordermeta = new MeOrderGoimon();
		$ordermeta->setMetaKey('GOIMON_ORDER');
		$ordermeta->setMetaValue($arr_order);
		$ordermeta->setOrder($order);
		$em->persist($ordermeta);
		$em->flush();
		$ordermeta = new MeOrderGoimon();
		$ordermeta->setMetaKey('GOIMON_NOIDUNG');
		$ordermeta->setMetaValue($arr_order->NoiDung);
		$ordermeta->setOrder($order);
		$em->persist($ordermeta);
		$em->flush();
		$response = new Response(json_encode(array('orderId' => $order->getId(), 'orderStatus' => $order->getOrderStatus())));
		$response->headers->set('Content-Type', 'application/json');
		return $response;
    }
    /**
     * @Route("/goimon/{order_id}/check", name="api_goimon_check")
     */

	public function goimonCheckAction($order_id){
        $em = $this->getDoctrine()->getEntityManager();

        $order = $em->getRepository('MoexCoreBundle:MeOrders')->find($order_id);

        if (!$order) {
			$response = new Response(json_encode(array('orderId' => $order_id, 'orderStatus' => 'DELETED')));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
        }
		$response = new Response(json_encode(array('orderId' => $order->getId(), 'orderStatus' => $order->getOrderStatus())));
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}
}
