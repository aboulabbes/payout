<?php


namespace App\Controller;
use App\Entity\Payout;
use App\Repository\PayoutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * Class payoutController
 * @package App\Controller
 * @Route("/payout")
 */

class payoutController
{
    /**
     * @Route(name="api_payout_collection_get",methods={"GET"})
     * @param PayoutRepository $payoutRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function collection(PayoutRepository $payoutRepository,SerializerInterface $serializer):JsonResponse
    {
        return new JsonResponse($serializer->serialize($payoutRepository->findAll(),'json'),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }


    /**
     * @Route(name="api_payout_collection_post", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param UrlGeneratorInterface $urlGenerator
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function post(Request $request,SerializerInterface $serializer,
                         UrlGeneratorInterface $urlGenerator,
                         EntityManagerInterface $entityManager): JsonResponse {


        $items = $serializer->deserialize($request->getContent(),'App\Entity\Item[]','json');
        $payouts = [];
        $payoutsList = [];
        foreach ($items as $item){
            $seller = $item->getSellerReference();
            $currency = $item->getPriceCurrency();
            $amount = $item->getPriceAmount();

            if(is_array($payouts) && array_key_exists($seller,$payouts)
                && array_key_exists($currency,$payouts[$seller])){

                $payout = $payouts[$seller][$currency];

                if(Payout::CONSTANT > $payout->getAmount()+ $amount)
                {
                    $payout->addItem($item);
                    $payouts[$seller][$currency] = $payout;
                    $payoutsList[]=$payout;
                }else{
                    $payoutsList[]=$payout;
                    $payout = new Payout();
                    $payout->setCurrency($currency);
                    $payout->setSellerReference($seller);
                    $payout->addItem($item);
                    $payouts[$seller][$currency] = $payout;
                }
            }else
            {
                $payout = new Payout();
                $payout->setCurrency($currency);
                $payout->setSellerReference($seller);
                $payout->addItem($item);
                $payouts[$seller][$currency] = $payout;

            }
        }


        foreach ($payouts as $sellerList) {
            foreach ($sellerList as $payout) {
                $entityManager->persist($payout);
                $payoutsList[]=$payout;
            }
        }

        $entityManager->flush();
        return new JsonResponse(
            $serializer->serialize($payoutsList, "json"),
            JsonResponse::HTTP_CREATED,
            ["Location" => $urlGenerator->generate("api_payout_collection_get")],
            true
        );
    }
}