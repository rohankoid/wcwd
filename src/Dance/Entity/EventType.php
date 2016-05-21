<?php

namespace Dance\Entity;


class EventType
{

    /**
     * Event idevent_type.
     *
     * @var integer
     */
    protected $idevent_type;

    /**
     * Event type.
     *
     * @var string
     */
    protected $type;

    /**
     * When the event entity was created.
     *
     * @var Datetime
     */
    protected $created_at;

    /**
     * @return int
     */
    public function getIdeventType()
    {
        return $this->idevent_type;
    }

    /**
     * @param int $idevent_type
     */
    public function setIdeventType($idevent_type)
    {
        $this->idevent_type = $idevent_type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return Datetime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param Datetime $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }


}
