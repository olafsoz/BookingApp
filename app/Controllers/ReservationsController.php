<?php

namespace App\Controllers;

use App\Database;
use App\Redirect;
use App\View;
use Carbon\CarbonPeriod;
use DateTime;

class ReservationsController
{
    public function viewOne($vars): View
    {
        $info = ApartmentsController::extracted($vars['id'])->getDateUntil();
        $date1 = new DateTime($info);
        $date2 = date("Y-m-d");
        $date3 = new DateTime($date2);

        $bookings = Database::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('reservations')
            ->where('apartment_id = ?')
            ->setParameter(0, $vars['id'])
            ->executeQuery()
            ->fetchAllAssociative();
        $list = [];
        $booked = [];
        if (!empty($bookings)) {
            foreach ($bookings as $booking) {
                $booked[] = [$booking['booked_from'], $booking['booked_until']];
            }
        }
        foreach ($booked as $record) {
            [$startDate, $endDate] = $record;
            $period = CarbonPeriod::create($startDate, $endDate);
            foreach ($period as $date) {
                $list[] = $date->format('Y-m-d');
            }
        }

        return new View('Website/viewOne', [
            'name' => $_SESSION['current_user']['name'] ?? '',
            'a' => ApartmentsController::extracted($vars['id']),
            'listingDate' => $date1,
            'dateNow' => $date3,
            'reservations' => $list
        ]);
    }

    public function reservationsAll(): View
    {
        $bookings = Database::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('reservations')
            ->where('booker_id = ?')
            ->setParameter(0, $_SESSION['current_user']['user_id'])
            ->executeQuery()
            ->fetchAllAssociative();
        if (empty($bookings)) {
            return new View('Website/reservations', [
                'name' => $_SESSION['current_user']['name'] ?? '',
            ]);
        }
        $arr = [];
        $statement = [];
        $sum = [];
        $i = 0;
        foreach ($bookings as $booking) {
            $info = Database::connection()
                ->createQueryBuilder()
                ->select('*')
                ->from('listings')
                ->where('id = ?')
                ->setParameter(0, $booking['apartment_id'])
                ->executeQuery()
                ->fetchAssociative();
            $range = CarbonPeriod::create($booking['booked_from'], $booking['booked_until']);
            foreach ($range as $add) {
                $arr[] = $add->format('Y-m-d');
            }
            $sum[] = $info['price'] * count($arr);
            $statement[$i]['title'] = $info['title'];
            $statement[$i]['address'] = $info['address'];
            $statement[$i]['price'] = $info['price'] * count($arr);
            $statement[$i]['days'] = count($arr);
            $statement[$i]['id'] = $booking['id'];
            unset($arr);
            $i++;
        }

        return new View('Website/reservations', [
            'name' => $_SESSION['current_user']['name'] ?? '',
            'statements' => $statement,
            'sum' => array_sum($sum),
            'bookings' => count($bookings)
        ]);
    }

    public function reserve($vars): Redirect
    {
        $date1 = $_POST['date_from'];
        $date2 = $_POST['date_until'];
        $s1 = explode('/', $date1);
        $s2 = explode('/', $date2);
        $realDateFrom = "$s1[2]-$s1[0]-$s1[1]";
        $realDateUntil = "$s2[2]-$s2[0]-$s2[1]";
        Database::connection()
            ->insert('reservations', [
                'apartment_id' => $vars['id'],
                'booker_id' => $_SESSION['current_user']['user_id'],
                'booked_from' => $realDateFrom,
                'booked_until' => $realDateUntil,
            ]);
        return new Redirect('/main');
    }

    public function deleteBooking($vars): Redirect
    {
        Database::connection()
            ->delete('reservations', [
                'id' => $vars['id']
            ]);
        return new Redirect('/reservations');
    }
}