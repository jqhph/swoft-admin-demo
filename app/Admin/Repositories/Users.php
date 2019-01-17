<?php

namespace App\Admin\Repositories;

use App\Models\Entity\UserAddress;
use App\Models\Entity\UserProfiles;
use Swoft\Admin\Bean\Annotation\AdminRepository;
use Swoft\Admin\Form;
use Swoft\Admin\Grid\Model;
use App\Controllers\Admin\UsersController;
use App\Models\Entity\Users as UsersEntity;
use Swoft\Admin\Repository\AbstractRepository;
use Swoft\Admin\Show;
use Swoft\Http\Server\Exception\RouteNotFoundException;

/**
 * @AdminRepository(UsersController::class)
 */
class Users extends AbstractRepository
{
    /**
     * 实体类类名
     *
     * @var string
     */
    protected $entityClass = UsersEntity::class;

    /**
     * 编辑页数据获取接口
     *
     * @param Form $form
     * @return array|string
     */
    public function findForEdit(Form $form)
    {
        $id = $form->getId();

        $user = UsersEntity::findById($id)->getResult();
        if (!$user) {
            throw new RouteNotFoundException();
        }

        $user = $user->toArray();

        $user['address'] = $user['profile'] = [];

        $address = UserAddress::query()->where('user_id', $id)->one()->getResult();
        if ($address) {
            $user['address'] = $address->toArray();
        }

        $profile = UserProfiles::query()->where('user_id', $id)->one()->getResult();
        if ($profile) {
            $user['profile'] = $profile->toArray();
        }

        return $user;
    }

    /**
     * 新增操作
     *
     * @param Form $form
     * @return int|string
     */
    public function insert(Form $form)
    {
        // 获取表单数据
        $insers = $form->getAttributes();

        $date = date('Y-m-d H:i:s');
        $insers['created_at'] = $date;
        $insers['updated_at'] = $date;

        $address = $insers['address'];
        $profile = $insers['profile'];

        unset($insers['address'], $insers['profile']);

        $userId = (new UsersEntity)->fill($insers)->save()->getResult();

        if ($userId) {
            $address['user_id'] = $userId;
            $profile['user_id'] = $userId;

            (new UserAddress)->fill($address)->save()->getResult();
            (new UserProfiles)->fill($profile)->save()->getResult();
        }

        return (int)$userId;
    }

    /**
     * 更新操作
     *
     * @param Form $form
     * @return bool|string
     */
    public function update(Form $form)
    {
        $id = $form->getId();
        $updates = $form->getAttributes(true);

        $date = date('Y-m-d H:i:s');

        // 由于swoft实体没有自动更新created_at字段的功能,所以新增或编辑时需要手动加
        $updates['updated_at'] = $date;

        // 密码为空不修改
        if (empty($updates['password'])) {
            unset($updates['password']);
        }

        // $updates字段名已由驼峰转化为下划线
        $address = $updates['address'] ?? [];
        $profile = $updates['profile'] ?? [];

        unset($updates['address'], $updates['profile']);

        $address && UserAddress::updateOne($address, ['user_id' => $id])->getResult();
        $profile && UserProfiles::updateOne($profile, ['user_id' => $id])->getResult();

        return UsersEntity::updateOne($updates, ['id' => $id])->getResult();
    }

}
