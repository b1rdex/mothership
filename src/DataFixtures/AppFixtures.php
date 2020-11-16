<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\Terminal;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getFixtures() as $object) {
            $manager->persist($object);
        }
        $manager->flush();
    }

    /**
     * @return iterable<object>
     */
    private function getFixtures(): iterable
    {
        yield from $this->createUsers();
        yield from $this->createTerminalsAndOrders();
    }

    /**
     * @return iterable<User>
     */
    private function createUsers(): iterable
    {
        $user = new User();
        $user->setEmail('admin@mothership');
        $user->setPassword($this->passwordEncoder->encodePassword($user, '12345'));
        yield $user;
    }

    /**
     * @return iterable<Terminal>
     */
    private function createTerminalsAndOrders(): iterable
    {
        yield from $this->usdBib();
        yield from $this->usdBob();
    }

    /**
     * @return iterable<Terminal>
     */
    private function usdBib(): iterable
    {
        $main = new Terminal();
        $main->setCode('biba');
        $main->setDescription('the first of the last');
        $main->setIsMain(true);
        $main->setBalance('4000.20');
        $main->setFreeMargin('4000.20');
        $tickerSymbol = 'USDBIB';
        $main->setTickerSymbol($tickerSymbol);

        $order = new Order();
        $order->setMagicNumber('100500');
        $order->setType(Order::TYPE_BUY);
        $order->setLots(1);
        $order->setOpenPrice('16.20');
        $order->setStatus(Order::STATUS_OPEN);
        $order->setTickerSymbol($tickerSymbol);
        $main->addOrder($order);

        $order = new Order();
        $order->setMagicNumber('100501');
        $order->setType(Order::TYPE_SELL);
        $order->setLots(1);
        $order->setOpenPrice('16.22');
        $order->setStatus(Order::STATUS_CLOSED);
        $order->setTickerSymbol($tickerSymbol);
        $main->addOrder($order);

        yield $main;

        $slave = new Terminal();
        $slave->setCode('biba slave');
        $slave->setDescription('posledovatel');
        $slave->setIsMain(false);
        $slave->setBalance('15');
        $slave->setFreeMargin('15');
        $slave->setTickerSymbol($tickerSymbol);

        $order = new Order();
        $order->setMagicNumber('9000');
        $order->setType(Order::TYPE_BUY);
        $order->setLots(1);
        $order->setOpenPrice('16.21');
        $order->setStatus(Order::STATUS_OPEN_ERROR);
        $order->setTickerSymbol($tickerSymbol);
        $slave->addOrder($order);

        yield $slave;
    }

    /**
     * @return iterable<Terminal>
     */
    private function usdBob(): iterable
    {
        $terminal = new Terminal();
        $terminal->setCode('boba');
        $terminal->setDescription('da hui ego znaet');
        $terminal->setIsMain(true);
        $terminal->setBalance('15.20');
        $terminal->setFreeMargin('15.20');
        $terminal->setTickerSymbol('USDBOB');

        yield $terminal;
    }
}
