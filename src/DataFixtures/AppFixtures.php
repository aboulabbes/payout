<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Item;
use App\Entity\Payout;

/**
 * Class AppFixtures
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $payout1 = new Payout();
        $payout1->setSellerReference("seller1");

        $payout2 = new Payout();
        $payout2->setSellerReference("seller2");

        for($i=0;$i<=10;$i++)
        {
            $item = new Item();
            $item->setItem(sprintf("item_%d" ,$i));
            $item->setPriceCurrency('EUR');
            $item->setPriceAmount($i*10);
            if($i%2==0){
                $amount1=$i*10;
                $payout1->setAmount($amount1);
                $payout1->setCurrency('EUR');
                $payout1->addItem($item);
            }else{
                $amount2=$i*10;
                $payout2->setCurrency('EUR');
                $payout2->setAmount($amount2);
                $payout2->addItem($item);
            }
            $item->setSellerReference(sprintf("reference_%d" ,$i));
        }
        $manager->persist($payout1);
        $manager->persist($payout2);
        $manager->flush();
    }
}
