<?php

namespace Dance\Model;

use Doctrine\DBAL\Connection;
use Dance\Entity\Event;
use \DateTime;

/**
 * Event repository
 */
class EventModel implements ModelInterface
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;

    CONST prefix = 'wcwd_';

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Saves the event to the database.
     *
     * @param \Dance\Entity\Event $event
     */
    public function save($event)
    {
        $eventData = array(
            'id' => $event->getIdevent(),
            'venue' => $event->getVenue(),
            'idevent_type' => $event->getIdeventType(),
            'iduser' => $event->getIdUser(),
            'contact_number' => $event->getContactNumber(),
            'dance_stype' => $event->getDanceStyle(),
            'teacher' => $event->getTeacher(),
            'going' => $event->getGoing(),
            'start_date' => $event->getStartDate(),
            'end_date' => $event->getEndDate(),
            'description' => $event->getDescription(),
        );

        if ($event->getIdEvent()) {
            $this->db->update(self::prefix.'event_type', $eventData, array('idevent' => $event->getIdEvent()));
        }
        else {
            // The event is new, note the creation timestamp.
            $now = new DateTime();
            $created_at = $now->format('Y-m-d H:i:s');
            $eventData['created_at'] = $created_at;
            $this->db->insert(self::prefix.'event', $eventData);
            // Get the id of the newly created event and set it on the entity.
            $id = $this->db->lastInsertId();
            $event->setIdEvent($id);
        }
    }

    /**
     * Deletes the event.
     *
     * @param \Dance\Entity\Event $event
     */
    public function delete($event)
    {
        return $this->db->delete(self::prefix.'event', array('idevent' => $event->getIdEvent()));
    }

    /**
     * Returns the total number of events.
     *
     * @return integer The total number of events.
     */
    public function getCount() {
        return $this->db->fetchColumn('SELECT COUNT(idevent) FROM wcwd_event');
    }

    /**
     * Returns an event matching the supplied id.
     *
     * @param integer $id
     *
     * @return \Dance\Entity\Event|false An entity object if found, false otherwise.
     */
    public function find($id)
    {
        $eventData = $this->db->fetchAssoc('SELECT * FROM wcwd_event WHERE idevent = ?', array($id));
        return $eventData ? $this->buildEvent($eventData) : FALSE;
    }

    /**
     * Returns a collection of events, sorted by name.
     *
     * @param integer $limit
     *   The number of events to return.
     * @param integer $offset
     *   The number of events to skip.
     * @param array $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return array A collection of events, keyed by event id.
     */
    public function findAll($limit, $offset = 0, $orderBy = array())
    {
        // Provide a default orderBy.
        if (!$orderBy) {
            $orderBy = array('venue' => 'ASC');
        }

        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select('a.*')
            ->from(self::prefix.'event', 'a')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('a.' . key($orderBy), current($orderBy));
        $statement = $queryBuilder->execute();
        $eventsData = $statement->fetchAll();

        $events = array();
        foreach ($eventsData as $eventData) {
            $eventId = $eventData['idevent'];
            $events[$eventId] = $this->buildEvent($eventData);
        }
        return $events;
    }

    /**
     * Instantiates an event entity and sets its properties using db data.
     *
     * @param array $eventData
     *   The array of db data.
     *
     * @return \Dance\Entity\Event
     */
    protected function buildEvent($eventData)
    {
        $event = new Event();
        $event->setIdEvent($eventData['idevent']);
        $event->setIdeventType($eventData['idevent_type']);
        $event->setIduser($eventData['iduser']);
        $event->setVenue($eventData['venue']);
        $event->setContactNumber($eventData['contact_number']);
        $event->setDanceStyle($eventData['dance_style']);
        $event->setTeacher($eventData['teacher']);
        $event->setGoing($eventData['going']);
        $event->setStartDate($eventData['start_date']);
        $event->setEndDate($eventData['end_date']);
        $event->setDescription($eventData['description']);
        $event->setCreatedAt($eventData['created_at']);
        return $event;
    }
}
