<?php

namespace Dance\Entity;


class Event
{
    /**
     * Event idevent.
     *
     * @var integer
     */
    protected $idevent;

    /**
     * Event idevent_type.
     *
     * @var integer
     */
    protected $idevent_type;

    /**
     * Event iduser.
     *
     * @var integer
     */
    protected $iduser;

    /**
     * Event name.
     *
     * @var string
     */
    protected $venue;

    /**
     * Event dance style.
     *
     * @var string
     */
    protected $contact_number;

    /**
     * Event dance style.
     *
     * @var string
     */
    protected $dance_style;

    /**
     * Event teacher.
     *
     * @var string
     */
    protected $teacher;

    /**
     * Event Start Date
     *
     * @var DATE
     */
    protected $start_date;

    /**
     * Event End Date
     *
     * @var DATE
     */
    protected $end_date;

    /**
     * Number of going an event has received.
     *
     * @var integer
     */
    protected $going;

    /**
     * Description of event
     *
     * @var description
     */
    protected $description;

    /**
     * When the event entity was created.
     *
     * @var Datetime
     */
    protected $created_at;


    public function getIdEvent()
    {
        return $this->idevent;
    }

    public function setIdEvent($idevent)
    {
        $this->idevent = $idevent;
    }

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
     * @return int
     */
    public function getIduser()
    {
        return $this->iduser;
    }

    /**
     * @param int $iduser
     */
    public function setIduser($iduser)
    {
        $this->iduser = $iduser;
    }

    /**
     * @return string
     */
    public function getDanceStyle()
    {
        return $this->dance_style;
    }

    /**
     * @param string $dance_style
     */
    public function setDanceStyle($dance_style)
    {
        $this->dance_style = $dance_style;
    }

    /**
     * @return DATE
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * @param DATE $end_date
     */
    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;
    }

    public function getVenue()
    {
        return $this->venue;
    }

    public function setVenue($venue)
    {
        $this->venue = $venue;
    }

    /**
     * @return string
     */
    public function getContactNumber()
    {
        return $this->contact_number;
    }

    /**
     * @param string $contact_number
     */
    public function setContactNumber($contact_number)
    {
        $this->contact_number = $contact_number;
    }

    public function getTeacher()
    {
        return $this->teacher;
    }

    public function setTeacher($teacher)
    {
        $this->teacher = $teacher;
    }

    public function getStartDate()
    {
        return $this->start_date;
    }

    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;
    }

    public function getGoing()
    {
        return $this->going;
    }

    public function setGoing($going)
    {
        $this->going = $going;
    }

    /**
     * @return description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param description $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }


    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

}
