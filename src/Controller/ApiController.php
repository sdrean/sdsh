<?php

namespace App\Controller;

use App\Entity\Coordinates;
use App\Entity\Device;
use App\Entity\Point;
use App\Entity\PurchaseType;
use App\Entity\Receipt;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api")
 * Class ApiController
 * @package App\Controller
 */
class ApiController extends AbstractController
{

    /**
     * @Route("/getpurchasetype", name="get_purchase_type")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Exception
     */
    public function getPurchaseType(Request $request, EntityManagerInterface $em)
    {

        $ptUtil = $em->getRepository('App\\Entity\\PurchaseType');
        $all = $ptUtil->findAll();

        $return = [];
        if(count($all) > 0){
            /** @var PurchaseType $pu */
            foreach($all as $pu){
                $return[] = [
                    'PurchaseTypeId' => $pu->getId(),
                    'PurchaseTypeName' => $pu->getPurchaseName()
                ];
            }
            return new JsonResponse(['valid' => true,'result' => $return]);
        }

        return new JsonResponse(['valid' => false]);
    }

    /**
     * @Route("/sendamount", name="send_amount")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Exception
     */
    public function sendAmount(Request $request, EntityManagerInterface $em)
    {
        $params = array();
        $content = $request->getContent();

        if (!empty($content))
        {
            $params = json_decode($content, true);

        }

        if (!array_key_exists('ptid',$params)) {
            return new JsonResponse(["valid" => false, "error" => "Missing type parameter"]);
        }
        $pt = $em->getRepository('App\\Entity\\PurchaseType')->find($params['ptid']);

        $receipt = new Receipt();
        $receipt
            ->setPurchaseType($pt);
        $receipt
            ->setAmount($params['amount']);
        $receipt
            ->setPurchaseDate(new \DateTime());
        $em->persist($receipt);
        $em->flush();

        return new JsonResponse(['valid' => true,'id' => $receipt->getId()]);
    }
}