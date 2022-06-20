<?php
namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CreateOrderPDF extends AbstractController
{
  const PDF = 'pdf';
  private $em;
  private $publicDirectory;
  public function __construct(EntityManagerInterface $em, ParameterBagInterface $params) {
    $this->em = $em;
    $this->publicDirectory = $params->get('pdf_directory');
  }
  public function createPDF($order)
  {
    $em = $this->em;
    $pdfOrder = '/order/orderInfo.html.twig';
    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');
    $dompdf = new Dompdf($pdfOptions);
    $orderId = $order->getId();
    $customer_id = $order->getCustomer();
    $customer = $em->getRepository(User::class)->find($customer_id);
    $customerEmail = $customer->getEmail();
    $orderItems = $order->getOrderItem();

    $html = $this->renderView(self::PDF.$pdfOrder, [
      'title' => "The Order was created on the ". date("Y-m-d") . " at " . date("h:i:s"),
      'orderId' => $orderId,
      'customerEmail' => $customerEmail,
      'orderItems' => $orderItems
    ]);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $output = $dompdf->output();
    $pdfFilepath =  $this->publicDirectory. '/order-'. $orderId .'.pdf';
    file_put_contents($pdfFilepath, $output);
    return new Response("The PDF file has been succesfully generated!");
  }
}