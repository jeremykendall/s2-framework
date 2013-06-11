<?php

namespace Calendar\Controller;

use Calendar\Model\LeapYear;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LeapYearController
{
    public function indexAction(Request $request, $year)
    {
        $leapYear = new LeapYear();

        if ($leapYear->isLeapYear($year)) {
            return new Response("Yep, it's a leap year");
        }

        return new Response("Nope, it's not a leap year");
    }
}
