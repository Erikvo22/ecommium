<?php

namespace App\Document;

use App\Repository\ProcessesRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass=ProcessesRepository::class)
 */
class Processes
{

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @var integer
     * @MongoDB\Field(type="integer")
     */
    protected $type; //TODO: lo ideal es que este campo tire de otra tabla que contenga los diversos tipos

    /**
     * @var string
     * @MongoDB\Field(type="string")
     */
    protected $input;

    /**
     * @var string
     * @MongoDB\Field(type="string")
     */
    protected $output;

    /**
     * @var \DateTime
     * @MongoDB\Field(type="date")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @MongoDB\Field(type="date")
     */
    protected $startedAt;

    /**
     * @var \DateTime
     * @MongoDB\Field(type="date")
     */
    protected $finishedAt;

    /**
     * @var integer
     * @MongoDB\Field(type="integer")
     */
    protected $status;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getInput(): string
    {
        return $this->input;
    }

    /**
     * @param string $input
     */
    public function setInput(string $input)
    {
        $this->input = $input;
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param string $output
     */
    public function setOutput(string $output)
    {
        $this->output = $output;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return bool
     */
    public function hasCreatedAt(): bool
    {
        return (bool)$this->createdAt;
    }

    /**
     * @MongoDB\PrePersist
     * @MongoDB\PreUpdate
     * @return static
     */
    public function touchCreatedAt()
    {
        $this->createdAt = $this->createdAt ?? new \DateTime();

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * @return bool
     */
    public function hasStartedAt(): bool
    {
        return (bool)$this->startedAt;
    }

    /**
     * @MongoDB\PrePersist
     * @MongoDB\PreUpdate
     * @return static
     */
    public function touchStartedAt()
    {
        $this->startedAt = new \DateTime();
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }

    /**
     * @return bool
     */
    public function hasFinishedAt(): bool
    {
        return (bool)$this->finishedAt;
    }

    /**
     * @MongoDB\PrePersist
     * @MongoDB\PreUpdate
     * @return static
     */
    public function touchFinishedAt()
    {
        $this->finishedAt = new \DateTime();
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
    }
}