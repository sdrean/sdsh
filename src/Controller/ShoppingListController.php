<?php


namespace App\Controller;


use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Zone;

/**
 * @Route("/api")
 * Class ShoppingListController
 * @package App\Controller
 */
class ShoppingListController extends AbstractController
{
    /**
     * @Route("/zones", name="api_zones")
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function getZones(EntityManagerInterface $em)
    {
        $list = $em
            ->getRepository('App\\Entity\\Zone')
            ->findBy([],[
                'order' => 'ASC'
        ]);

        $return = [];
        if(count($list) > 0){
            /** @var Zone $zone */
            foreach($list as $zone){
                $return[] = [
                    'id' => $zone->getId(),
                    'name' => $zone->getName(),
                    'icon' => $zone->getIcon(),
                    'color' => $zone->getColor(),
                ];
            }
        }

        return new JsonResponse([
            'valid' => true,
            'result' => $return
        ]);
    }

    /**
     * @Route("/products", name="api_products")
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function getProducts(EntityManagerInterface $em)
    {
        $list = $em
            ->getRepository('App\\Entity\\Product')
            ->findBy([],[
                'name' => 'ASC'
            ]);
        $return = [];
        if(count($list) > 0){
            /** @var Product $product */
            foreach($list as $product){
                $return[] = [
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'zone' => $product->getZone()->getId()
                ];
            }
        }
        return new JsonResponse([
            'valid' => true,
            'result' => $return
        ]);
    }

    /**
     * @Route("/product/add", name="api_add_product")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function addProduct(Request $request, EntityManagerInterface $em)
    {
        $params = array();
        $content = $request->getContent();

        if (!empty($content)) {
            $params = json_decode($content, true);
        }

        if (!array_key_exists('name',$params)) {
            return new JsonResponse(["valid" => false, "error" => "Missing name parameter"]);
        }

        if (!array_key_exists('zone',$params)) {
            return new JsonResponse(["valid" => false, "error" => "Missing zone parameter"]);
        }

        $zone = $em->getRepository('App\\Entity\\Zone')->find($params['zone']);

        $product = new Product();
        $product
            ->setName($params['name'])
            ->setZone($zone);
        $em->persist($product);
        $em->flush();

        return new JsonResponse([
            'valid' => true,
            'result' => [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'zone' => $product->getZone()->getId()
            ]
        ]);
    }

    public function addToList(Request $request)
    {

    }

    public function removeToList(Request $request)
    {

    }

    public function createNewList()
    {

    }
}