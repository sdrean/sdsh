<?php


namespace App\Service;


use App\Entity\ShoppingList;
use App\Entity\ShoppingListItem;
use Doctrine\ORM\EntityManagerInterface;

class ShoppingListManager
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function retreiveCurrentList()
    {
        // We create it if not exists
        $shoppingListUtil = $this->em->getRepository('App\\Entity\\ShoppingList');
        $currentList = $shoppingListUtil->findCurrentList();
        if($currentList == null){
            $currentList = new ShoppingList();
            $currentList->setStatus('OPEN');
            $currentList->setUpdateDate(new \DateTime());
            $currentList->setCreateDate(new \DateTime());
            $em->persist($currentList);
            $em->flush();
        }

        $items = $this->em->getRepository('App\\Entity\\ShoppingListItem')->findCurrentListItem($currentList);

        $return  = [
            'id' => $currentList->getId(),
            'status' => $currentList->getStatus(),
            'items' => []
        ];

        if(count($items) > 0){
            /** @var ShoppingListItem $item */
            foreach ($items as $item){
                $return['items'][] = [
                    'id' => $item->getId(),
                    'product' => $item->getProduct()->getName()
                ];
            }
        }

        return $return;
    }
}