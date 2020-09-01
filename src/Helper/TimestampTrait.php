<?php
/**
 * Trait for Timestampable entities
 *
 * @package   App\Helper
 * @version   1.0.0
 * @author    Rayzen-dev <rayzen.dev@gmail.com>
 * @copyright no copyrights
 */

namespace App\Helper;

/**
 * Trait TimestampTrait
 */
trait TimestampTrait
{
    private $createdAt;

    private $updatedAt;

    private $deletedAt;

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param mixed $deletedAt
     */
    public function setDeletedAt($deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
