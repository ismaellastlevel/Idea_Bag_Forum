<?php


namespace App\EventSubscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UpdateTimestampSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SET_DATA => 'onPostSetData'
        ];
    }

    public function onPostSetData(FormEvent $event)
    {
        dd($event);
    }

}