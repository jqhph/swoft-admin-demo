<?php

namespace App\Controllers;

use App\Models\Entity\UserAddress;
use App\Models\Entity\UserProfiles;
use App\Models\Entity\Users;
use Swoft\Admin\Admin;
use Swoft\Admin\Widgets\Dump;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Faker\Factory as FakerFactory;

/**
 * @Controller("/faker")
 */
class FakerController
{
    /**
     * @RequestMapping("/faker")
     */
    public function faker()
    {
        $faker = FakerFactory::create();

        $data = [
            $faker->name,
            $faker->address,
            $faker->paragraph,
            $faker->sentence,
            $faker->email,
            $faker->company,
            $faker->url,
//            $faker->image(alias('@root/public/faker/images'))
        ];

        return html_response($faker->randomHtml);
    }

    /**
     * @RequestMapping()
     */
    public function users()
    {
        $max = 90;

        $faker = FakerFactory::create();

        $users = Users::query()->get(['id'])->getResult()->toArray();

        $profiles = $address = [];

        foreach ($users as $user) {
            $profiles[] = [
                'user_id' => $user['id'],
                'homepage' => $faker->url,
                'mobile' => $faker->phoneNumber,
                'document' => $faker->text,
                'gender' => $faker->numberBetween(0, 2),
                'birthday' => $faker->date(),
                'address' => $faker->address,
                'color' => $faker->hexColor,
                'age' => $faker->numberBetween(1, 110),
                'last_login_at' => $faker->date('Y-m-d H:i:s'),
                'last_login_ip' => $faker->ipv4,
                'created_at' => $faker->date('Y-m-d H:i:s'),
                'updated_at' => $faker->date('Y-m-d H:i:s'),
            ];

//            $address[] = [
//                'user_id' => $user['id'],
//                'address' => $faker->address,
//                'province_id' => $faker->numberBetween(0, 6),
//                'city_id' => $faker->numberBetween(0, 6),
//                'district_id' => $faker->numberBetween(0, 6),
//            ];
        }

//        UserAddress::batchInsert($address)->getResult();
        UserProfiles::batchInsert($profiles)->getResult();

        return $this;
    }
}
