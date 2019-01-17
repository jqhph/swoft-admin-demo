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
 * @Table(name="dm_users")
 * @uses      Users
 */
class Users extends Model
{
    /**
     * @var int $id 主键
     * @Id()
     * @Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string $name 
     * @Column(name="name", type="string", length=255, default="")
     */
    private $name;

    /**
     * @var string $email 
     * @Column(name="email", type="string", length=255, default="")
     */
    private $email;

    /**
     * @var int $avatar 
     * @Column(name="avatar", type="string", default=0)
     */
    private $avatar;

    /**
     * @var int $password 
     * @Column(name="password", type="string", default=0)
     */
    private $password;

    /**
     * @var string $createdAt 
     * @Column(name="created_at", type="timestamp")
     */
    private $createdAt;

    /**
     * @var string $updatedAt 更新时间
     * @Column(name="updated_at", type="timestamp")
     */
    private $updatedAt;

    /**
     * @var int $isDeleted
     * @Column(name="is_deleted", type="tinyint")
     */
    private $isDeleted;

    /**
     * 主键
     * @param int $value
     * @return $this
     */
    public function setId(int $value)
    {
        $this->id = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setName(string $value): self
    {
        $this->name = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setEmail(string $value): self
    {
        $this->email = $value;

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setAvatar(string $value): self
    {
        $this->avatar = $value;

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setPassword(string $value): self
    {
        $this->password = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setCreatedAt(string $value): self
    {
        $this->createdAt = $value;

        return $this;
    }

    /**
     * 更新时间
     * @param string $value
     * @return $this
     */
    public function setUpdatedAt(string $value): self
    {
        $this->updatedAt = $value;

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setIsDeleted(int $value)
    {
        $this->isDeleted = $value;

        return $this;
    }

    /**
     * 主键
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @return int
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * 更新时间
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return int
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

}
