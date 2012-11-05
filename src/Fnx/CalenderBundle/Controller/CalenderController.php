<?php

namespace Fnx\CalenderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/calendario")
 */
class CalenderController extends Controller
{
    /**
     * @Route("/", name="calenderHome")
     * @Template()
     */
    public function indexAction()
    {
         \YsJQuery::useComponent(\YsJQueryConstant::COMPONENT_JQFULL_CALENDAR);
    
    $calendar = new \YsFullCalendar('myCalendarId');

    $event = new \YsCalendarEvent('eventId','My Event Title');
    $event->setStart(new \DateTime());
    $event->setEnd(new \DateTime('+1 day'));
    $event->setAllDay(false);
    $event->setColor('red');
    $calendar->addEvent($event);

    $event = new \YsCalendarEvent(123456,'Event 2');
    $event->setStart(new \DateTime());
    $event->setEnd(new \DateTime('+2 hour'));
    $event->setAllDay(false);
    $event->setColor('green');
    $event->

    $calendar->addEvent($event);
    
    return array('calendar' => $calendar);
    }
}
