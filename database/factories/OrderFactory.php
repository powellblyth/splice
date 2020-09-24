<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'number'          => 'CR-'. $this->faker->numberBetween(1000,100000),
            'customer_name' => $this->faker->name,
            'customer_telephone' => $this->faker->phoneNumber,
            'customer_email' => $this->faker->email,
            'customer_guid' => Str::uuid(),
            'customer_mobile_telephone' => $this->faker->phoneNumber,
            'customer_id' => $this->faker->numberBetween(1000000,9000000000),
            'guid' => Str::uuid(),
            'status' => 'new',
            'warehouse' => 'WALKER',
            'source_status' => 'Placed',
            'order_date' => $this->faker->date('y-m-d H:i:s','now'),
            'source' => 'Unleashed',
            'delivery_address_1'=>$this->faker->streetAddress,
            'delivery_address_2'=>$this->faker->secondaryAddress,
            'delivery_suburb'=>$this->faker->city,
            'delivery_city'=>$this->faker->city,
            'delivery_post_code'=>$this->faker->postcode,
            'delivery_country'=>$this->faker->country,
            'delivery_method'=>$this->faker->randomElement(['royalmail1st','royalmail2nd']),
            'tax_amount'=>$this->faker->randomElement(['royalmail1st','royalmail2nd']),
        ];
    }
}
