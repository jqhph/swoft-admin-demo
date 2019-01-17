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
 * @Table(name="dm_user_profiles")
 * @uses      UserProfiles
 */
class UserProfiles extends Model
{
    /**
     * @var int $userId 
     * @Column(name="user_id", type="integer", default=0)
     */
    private $userId;

    /**
     * @var string $homepage 
     * @Column(name="homepage", type="string", length=255, default="")
     */
    private $homepage;

    /**
     * @var string $mobile 
     * @Column(name="mobile", type="string", length=255, default="")
     */
    private $mobile;

    /**
     * @var int $document 
     * @Column(name="document", type="string", default=0)
     */
    private $document;

    /**
     * @var int $gender 
     * @Column(name="gender", type="tinyint", default=0)
     */
    private $gender;

    /**
     * @var string $birthday 
     * @Column(name="birthday", type="date")
     * @Required()
     */
    private $birthday;

    /**
     * @var string $address 
     * @Column(name="address", type="string", length=255, default="")
     */
    private $address;

    /**
     * @var string $color 
     * @Column(name="color", type="string", length=255, default="")
     */
    private $color;

    /**
     * @var int $age 
     * @Column(name="age", type="tinyint", default=0)
     */
    private $age;

    /**
     * @var string $lastLoginAt 
     * @Column(name="last_login_at", type="timestamp")
     */
    private $lastLoginAt;

    /**
     * @var string $lastLoginIp 
     * @Column(name="last_login_ip", type="string", length=255, default="")
     */
    private $lastLoginIp;

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
     * @param string $value
     * @return $this
     */
    public function setHomepage(string $value): self
    {
        $this->homepage = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setMobile(string $value): self
    {
        $this->mobile = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setDocument(string $value): self
    {
        $this->document = $value;

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setGender(int $value): self
    {
        $this->gender = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setBirthday(string $value): self
    {
        $this->birthday = $value;

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
     * @param string $value
     * @return $this
     */
    public function setColor(string $value): self
    {
        $this->color = $value;

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setAge(int $value): self
    {
        $this->age = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setLastLoginAt(string $value): self
    {
        $this->lastLoginAt = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setLastLoginIp(string $value): self
    {
        $this->lastLoginIp = $value;

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
     * @return string
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @return int
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @return string
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @return string
     */
    public function getLastLoginAt()
    {
        return $this->lastLoginAt;
    }

    /**
     * @return string
     */
    public function getLastLoginIp()
    {
        return $this->lastLoginIp;
    }

}
