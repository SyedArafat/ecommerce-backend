<?php

namespace App\Ecommerce\Product;

use App\Models\Order;
use App\Models\User;

class OrderService
{
    /**
     * @var User
     */
    private $user;

    /**
     * @param mixed $user
     * @return OrderService
     */
    public function setUser($user): OrderService
    {
        $this->user = $user;
        return $this;
    }


    public function storeOrder($data)
    {
        return Order::create(array_merge($data, ["user_id" => $this->user->id]));
    }

    public function getMyOrders()
    {
        return ($this->user->ordersMade()->with('product')->get());
    }
}
