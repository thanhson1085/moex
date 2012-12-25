<?php

namespace Moex\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Request\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Moex\CoreBundle\Entity\MeOrders;
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
     * @Route("/goimon/create", name="api_goimon_create")
     */
    public function goimonCreateAction()
    {
		$request = $this->getRequest();
		$j = $this->getRequest()->query->get('order');
		$j = str_replace('\"','"',$j);
		$j = str_replace('\/','/',$j);
		$arr_order = json_decode($j);
        $em = $this->getDoctrine()->getEntityManager();
		$order = new MeOrders();
		$order->setOrderCode($arr_order->MaDonHang);
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
		//$order->setOrderTime($arr_order->Ngay);
		$order->setOrderName($arr_order->TenNhaHang);
		$order->setPhone($arr_order->DTNhaHang);
		$order->setSenderAddress($arr_order->DTNhaHang);
		$order->setReceiverName($arr_order->NguoiNhan);
		$order->setReceiverPhone($arr_order->SDTNguoiNhan);
		$order->setReceiverPhone($arr_order->SDTNguoiNhan);
		$order->setRoadPrice($arr_order->PhiGiaoHang);
		$order->setPrice($arr_order->PhiGiaoHang);
		$order->setExtraPrice($arr_order->TongThanhToan);
		$order->setPromotion($arr_order->KhuyenMai);
		$order->setOrderInfo($arr_order->GhiChu);
		$order->setOrderStatus($this->container->getParameter("moex.order.status.pending"));
		$em->persist($order);
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
