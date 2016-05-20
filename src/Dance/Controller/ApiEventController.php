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
            $data[] = array(
                'id' => $event->getIdevent(),
                'venue' => $event->getVenue(),
                'idevent_type' => $event->getIdeventType(),
                'iduser' => $event->getIdUser(),
                'contact_number' => $event->getContactNumber(),
                'going' => $event->getGoing(),
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
        $data = array(
            'id' => $event->getId(),
            'name' => $event->getName(),
            'short_biography' => $event->getShortBiography(),
            'biography' => $event->getBiography(),
            'soundcloud_url' => $event->getSoundCloudUrl(),
            'likes' => $event->getLikes(),
        );

        return $app->json($data);
    }

    public function addAction(Request $request, Application $app)
    {
        if (!$request->request->has('name')) {
            return $app->json('Missing required parameter: name', 400);
        }
        if (!$request->request->has('short_biography')) {
            return $app->json('Missing required parameter: short_biography', 400);
        }

        $event = new Event();
        $event->setName($request->request->get('name'));
        $event->setShortBiography($request->request->get('short_biography'));
        $event->setBiography($request->request->get('biography'));
        $event->setSoundCloudUrl($request->request->get('soundcloud_url'));
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
        if (!$request->request->has('name')) {
            return $app->json('Missing required parameter: name', 400);
        }
        if (!$request->request->has('short_biography')) {
            return $app->json('Missing required parameter: short_biography', 400);
        }
        $event->setName($request->request->get('name'));
        $event->setShortBiography($request->request->get('short_biography'));
        $event->setBiography($request->request->get('biography'));
        $event->setSoundCloudUrl($request->request->get('soundcloud_url'));
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
