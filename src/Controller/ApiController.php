<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiController extends AbstractController
{
    /**
     * @param mixed $data
     */
    private function jsonResponse(int $status, bool $isOk, $data = null): JsonResponse
    {
        $json = [
            'ok' => $isOk,
        ];
        if ($isOk && $data) {
            $json['data'] = $data;
        }
        if (!$isOk && $data) {
            $json['error'] = $data;
        }

        return $this->json($json, $status);
    }

    /**
     * @Route("/api/order/create", name="api_order_create", methods="post")
     */
    public function orderCreate(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ): Response {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);

        $data = $request->getContent();
        if (!$data) {
            return $this->jsonResponse(400, false, 'No input json present');
        }
        $order = $serializer->deserialize($data, Order::class, 'json');
        $violations = $validator->validate($order);

        if (count($violations) > 0) {
            $errors = [];
            /** @var \Symfony\Component\Validator\ConstraintViolationInterface $violation */
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] =  $violation->getMessage();
            }
            return $this->jsonResponse(400, false, $errors);
        }

        $entityManager->persist($order);
        $entityManager->flush();

        return $this->jsonResponse(201, true);
    }

    /**
     * @Route("/api/orders/{since?}", name="api_orders", methods="get", requirements={"since"="\d+"})
     */
    public function ordersList(OrderRepository $repository, ?int $since = null): Response
    {
        return $this->jsonResponse(200, true, $repository->findForReplication($since));
    }
}
