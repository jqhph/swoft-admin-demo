<?php
namespace App\Models\Entity;

use Swoft\Admin\Database\Model;
use Swoft\Db\Bean\Annotation\Column;
use Swoft\Db\Bean\Annotation\Entity;
use Swoft\Db\Bean\Annotation\Id;
use Swoft\Db\Bean\Annotation\Required;
use Swoft\Db\Bean\Annotation\Table;
use Swoft\Db\Types;

/**
 * @Entity()
 * @Table(name="dm_user_address")
 * @uses      UserAddress
 */
class UserAddress extends Model
{
    /**
     * @var int $userId 
     * @Column(name="user_id", type="integer", default=0)
     */
    private $userId;

    /**
     * @var int $provinceId 
     * @Column(name="province_id", type="integer", default=0)
     */
    private $provinceId;

    /**
     * @var int $cityId 
     * @Column(name="city_id", type="integer", default=0)
     */
    private $cityId;

    /**
     * @var int $districtId 
     * @Column(name="district_id", type="integer", default=0)
     */
    private $districtId;

    /**
     * @var string $address 
     * @Column(name="address", type="string", length=255, default="")
     */
    private $address;

    /**
     * @param int $value
     * @return $this
     */
    public function setUserId(int $value): self
    {
        $this->userId = $value;

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setProvinceId(int $value): self
    {
        $this->provinceId = $value;

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setCityId(int $value): self
    {
        $this->cityId = $value;

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setDistrictId(int $value): self
    {
        $this->districtId = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setAddress(string $value): self
    {
        $this->address = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getProvinceId()
    {
        return $this->provinceId;
    }

    /**
     * @return int
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * @return int
     */
    public function getDistrictId()
    {
        return $this->districtId;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }


}
