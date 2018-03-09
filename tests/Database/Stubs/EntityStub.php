<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Framework\Database\Stubs;

use Doctrine\ORM\Mapping as ORM;
use EoneoPay\Framework\Database\Entities\Entity;

/**
 * @ORM\Entity()
 */
class EntityStub extends Entity
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     *
     * @var string
     */
    private $entityId;

    /**
     * @ORM\Column(name="string", type="string", nullable=true)
     *
     * @var string
     */
    private $string;

    /**
     * Get entity id.
     *
     * @return null|string
     */
    public function getEntityId(): ?string
    {
        return $this->entityId;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityNotFoundException(): string
    {
        return EntityStubNotFoundException::class;
    }

    /**
     * Get string.
     *
     * @return null|string
     */
    public function getString(): ?string
    {
        return $this->string;
    }

    /**
     * {@inheritdoc}
     */
    public function getValidationFailedException(): string
    {
        return EntityStubValidationFailedException::class;
    }

    /**
     * Set string.
     *
     * @param string $string
     *
     * @return \Tests\EoneoPay\Framework\Database\Stubs\EntityStub
     */
    public function setString(string $string): self
    {
        $this->string = $string;

        return $this;
    }

    /**
     * Serialize entity as an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [];
    }
}