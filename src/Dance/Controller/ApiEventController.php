<?php

namespace Dance\Controller;

use Dance\Entity\Event;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class ApiEventController
{
    public function indexAction(Request $request, Application $app)
    {
        $limit = $request->query->get('limit', 20);
        $offset = $request->query->get('offset', 0);
        $events = $app['model.event']->findAll($limit, $offset);
        $data = array();
        foreach ($events as $event) {
            $idevent_type = $event->getIdeventType();
            $event_type_obj =  $app['model.event_type']->find($idevent_type);
            $event_type = $event_type_obj->getType();
            $data[] = array(
                'id' => $event->getIdevent(),
                'venue' => $event->getVenue(),
                'event_type' => $event_type,
                'iduser' => $event->getIdUser(),
                'contact_number' => $event->getContactNumber(),
                'dance_stype' => $event->getDanceStyle(),
                'teacher' => $event->getTeacher(),
                'going' => $event->getGoing(),
                'start_date' => $event->getStartDate(),
                'end_date' => $event->getEndDate(),
                'description' => $event->getDescription(),
                'created_at' => $event->getCreatedAt(),
            );
        }

        return $app->json($data);
    }

    public function listAction(Request $request, Application $app)
    {
        $limit = $request->query->get('limit', 20);
        $offset = $request->query->get('offset', 0);
        $events = $app['model.event']->findAll($limit, $offset);
        $data = array();
        foreach ($events as $event) {
            $idevent_type = $event->getIdeventType();
            $event_type_obj =  $app['model.event_type']->find($idevent_type);
            $event_type = $event_type_obj->getType();
            $data[] = array(
                'id' => $event->getIdevent(),
                'venue' => $event->getVenue(),
                'event_type' => $event_type,
                'iduser' => $event->getIdUser(),
                'contact_number' => $event->getContactNumber(),
                'dance_stype' => $event->getDanceStyle(),
                'teacher' => $event->getTeacher(),
                'going' => $event->getGoing(),
                'start_date' => $event->getStartDate(),
                'end_date' => $event->getEndDate(),
                'description' => $event->getDescription(),
                'created_at' => $event->getCreatedAt(),
            );
        }

        return $app->json($data);
    }

    public function viewAction(Request $request, Application $app)
    {
        $event = $request->attributes->get('event');
        if (!$event) {
            return $app->json('Not Found', 404);
        }
        $idevent_type = $event->getIdeventType();
        $event_type_obj =  $app['model.event_type']->find($idevent_type);
        $event_type = $event_type_obj->getType();
        $data[] = array(
            'id' => $event->getIdevent(),
            'venue' => $event->getVenue(),
            'event_type' => $event_type,
            'iduser' => $event->getIdUser(),
            'contact_number' => $event->getContactNumber(),
            'dance_stype' => $event->getDanceStyle(),
            'teacher' => $event->getTeacher(),
            'going' => $event->getGoing(),
            'start_date' => $event->getStartDate(),
            'end_date' => $event->getEndDate(),
            'description' => $event->getDescription(),
            'created_at' => $event->getCreatedAt(),
        );

        return $app->json($data);
    }

    public function addAction(Request $request, Application $app)
    {
        if (!$request->request->has('venue')) {
            return $app->json('Missing required parameter: venue', 400);
        }
        if (!$request->request->has('idevent_type')) {
            return $app->json('Missing required parameter: idevent_type', 400);
        }
        if (!$request->request->has('iduser')) {
            return $app->json('Missing required parameter: iduser', 400);
        }
        if (!$request->request->has('start_date')) {
            return $app->json('Missing required parameter: start_date', 400);
        }
        if (!$request->request->has('end_date')) {
            return $app->json('Missing required parameter: end_date', 400);
        }

        $event = new Event();
        $event->setIdEvent($request->request->get('idevent'));
        $event->setIdeventType($request->request->get('idevent_type'));
        $event->setIduser($request->request->get('iduser'));
        $event->setVenue($request->request->get('venue'));
        $event->setContactNumber($request->request->get('contact_number'));
        $event->setDanceStyle($request->request->get('dance_style'));
        $event->setTeacher($request->request->get('teacher'));
        $event->setGoing($request->request->get('going'));
        $event->setStartDate($request->request->get('start_date'));
        $event->setEndDate($request->request->get('end_date'));
        $event->setDescription($request->request->get('description'));
        $app['model.event']->save($event);

        $headers = array('Location' => '/api/event/' . $event->getId());
        return $app->json('Created', 201, $headers);
    }

    public function editAction(Request $request, Application $app)
    {
        $event = $request->attributes->get('event');
        if (!$event) {
            return $app->json('Not Found', 404);
        }
        if (!$request->request->has('venue')) {
            return $app->json('Missing required parameter: venue', 400);
        }
        if (!$request->request->has('idevent_type')) {
            return $app->json('Missing required parameter: idevent_type', 400);
        }
        if (!$request->request->has('iduser')) {
            return $app->json('Missing required parameter: iduser', 400);
        }
        if (!$request->request->has('start_date')) {
            return $app->json('Missing required parameter: start_date', 400);
        }
        if (!$request->request->has('end_date')) {
            return $app->json('Missing required parameter: end_date', 400);
        }

        $event = new Event();
        $event->setIdEvent($request->request->get('idevent'));
        $event->setIdeventType($request->request->get('idevent_type'));
        $event->setIduser($request->request->get('iduser'));
        $event->setVenue($request->request->get('venue'));
        $event->setContactNumber($request->request->get('contact_number'));
        $event->setDanceStyle($request->request->get('dance_style'));
        $event->setTeacher($request->request->get('teacher'));
        $event->setGoing($request->request->get('going'));
        $event->setStartDate($request->request->get('start_date'));
        $event->setEndDate($request->request->get('end_date'));
        $event->setDescription($request->request->get('description'));
        $app['model.event']->save($event);

        return $app->json('OK', 200);
    }

    public function deleteAction(Request $request, Application $app)
    {
        $event = $request->attributes->get('event');
        if (!$event) {
            return $app->json('Not Found', 404);
        }
        $app['model.event']->delete($event);

        return $app->json('No Content', 204);
    }
}
