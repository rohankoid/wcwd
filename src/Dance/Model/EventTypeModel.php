<?php

namespace Dance\Model;

use Doctrine\DBAL\Connection;
use Dance\Entity\EventType;

/**
 * Event repository
 */
class EventTypeModel implements ModelInterface
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
     * @param \Dance\Entity\EventType $event
     */
    public function save($event)
    {
        $eventData = array(
            'type' => $event->getType(),
        );

        if ($event->getIdeventType()) {
            $this->db->update(self::prefix.'event_type', $eventData, array('idevent_type' => $event->getIdeventType()));
        }
        else {
            // The event type is new, note the creation timestamp.
            $now = new DateTime();
            $now->format('Y-m-d H:i:s');

            $this->db->insert(self::prefix.'event_type', $eventData);
            // Get the id of the newly created event type and set it on the entity.
            $id = $this->db->lastInsertId();
            $event->setIdeventType($id);
        }
    }

    /**
     * Deletes the event.
     *
     * @param \Dance\Entity\EventType $event
     */
    public function delete($event)
    {
        return $this->db->delete('events', array('idevent_type' => $event->getIdeventType()));
    }

    /**
     * Returns the total number of events.
     *
     * @return integer The total number of events.
     */
    public function getCount() {
        return $this->db->fetchColumn('SELECT COUNT(idevent_type) FROM wcwd_event_type');
    }

    /**
     * Returns an event matching the supplied id.
     *
     * @param integer $id
     *
     * @return \Dance\Entity\EventType|false An entity object if found, false otherwise.
     */
    public function find($id)
    {
        $eventData = $this->db->fetchAssoc('SELECT * FROM wcwd_event_type WHERE idevent_type = ?', array($id));
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
            ->from(self::prefix.'event_type', 'a')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('a.' . key($orderBy), current($orderBy));
        $statement = $queryBuilder->execute();
        $eventsData = $statement->fetchAll();

        $events = array();
        foreach ($eventsData as $eventData) {
            $eventId = $eventData['idevent_type'];
            $events[$eventId] = $this->buildEvent($eventData);
        }
        return $events;
    }

    /**
     * Instantiates an event type entity and sets its properties using db data.
     *
     * @param array $eventData
     *   The array of db data.
     *
     * @return \Dance\Entity\EventType
     */
    protected function buildEvent($eventData)
    {
        $event = new EventType();
        $event->setIdeventType($eventData['idevent_type']);
        $event->setType($eventData['type']);
        $event->setCreatedAt($eventData['created_at']);
        return $event;
    }
}
