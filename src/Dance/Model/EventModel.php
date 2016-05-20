<?php

namespace Dance\Model;

use Doctrine\DBAL\Connection;
use Dance\Entity\Event;

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
            'name' => $event->getName(),
            'short_biography' => $event->getShortBiography(),
            'biography' => $event->getBiography(),
            'soundcloud_url' => $event->getSoundCloudUrl(),
            'image' => $event->getImage(),
        );

        if ($event->getId()) {
            // If a new image was uploaded, make sure the filename gets set.
            $newFile = $this->handleFileUpload($event);
            if ($newFile) {
                $eventData['image'] = $event->getImage();
            }

            $this->db->update('events', $eventData, array('event_id' => $event->getId()));
        }
        else {
            // The event is new, note the creation timestamp.
            $eventData['created_at'] = time();

            $this->db->insert('events', $eventData);
            // Get the id of the newly created event and set it on the entity.
            $id = $this->db->lastInsertId();
            $event->setId($id);

            // If a new image was uploaded, update the event with the new
            // filename.
            $newFile = $this->handleFileUpload($event);
            if ($newFile) {
                $newData = array('image' => $event->getImage());
                $this->db->update('events', $newData, array('event_id' => $id));
            }
        }
    }

    /**
     * Handles the upload of an event image.
     *
     * @param \Dance\Entity\Event $event
     *
     * @param boolean TRUE if a new event image was uploaded, FALSE otherwise.
     */
    protected function handleFileUpload($event) {
        // If a temporary file is present, move it to the correct directory
        // and set the filename on the event.
        $file = $event->getFile();
        if ($file) {
            $newFilename = $event->getId() . '.' . $file->guessExtension();
            $file->move(WCWD_PUBLIC_ROOT . '/img/events', $newFilename);
            $event->setFile(null);
            $event->setImage($newFilename);
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Deletes the event.
     *
     * @param \Dance\Entity\Event $event
     */
    public function delete($event)
    {
        // If the event had an image, delete it.
        $image = $event->getImage();
        if ($image) {
            unlink('images/events/' . $image);
        }
        return $this->db->delete('events', array('event_id' => $event->getId()));
    }

    /**
     * Returns the total number of events.
     *
     * @return integer The total number of events.
     */
    public function getCount() {
        return $this->db->fetchColumn('SELECT COUNT(event_id) FROM events');
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
        $eventData = $this->db->fetchAssoc('SELECT * FROM events WHERE idevent = ?', array($id));
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
