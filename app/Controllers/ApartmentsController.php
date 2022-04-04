<?php

namespace App\Controllers;

use App\Database;
use App\Models\Apartment;
use App\Redirect;
use App\View;
use DateTime;


class ApartmentsController
{
    public static function extracted($id): object
    {
        $list = Database::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('listings')
            ->where('id = ?')
            ->setParameter(0, $id)
            ->executeQuery()
            ->fetchAssociative();

        $realName = Database::connection()
            ->createQueryBuilder()
            ->select('name')
            ->from('user_info')
            ->where('user_id = ?')
            ->setParameter(0, (int)$list['poster_id'])
            ->executeQuery()
            ->fetchAssociative();

        return new Apartment(
            $list['id'],
            $realName['name'] ?? 'User no longer exists',
            $list['title'],
            $list['description'],
            $list['address'],
            $list['rooms'],
            $list['date_from'],
            $list['date_until'],
            $list['price'],
            $list['photo']
        );
    }

    public function index(): View
    {
        $allListingInfo = Database::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('listings')
            ->executeQuery()
            ->fetchAllAssociative();

        $error = [];
        $everything = [];
        for ($i = 0; $i < count($allListingInfo); $i++) {
            $realName = Database::connection()
                ->createQueryBuilder()
                ->select('name')
                ->from('user_info')
                ->where('user_id = ?')
                ->setParameter(0, (int)$allListingInfo[$i]['poster_id'])
                ->executeQuery()
                ->fetchAssociative();

            $everything[] = new Apartment(
                $allListingInfo[$i]['id'],
                $realName['name'] ?? 'User no longer exists',
                $allListingInfo[$i]['title'],
                $allListingInfo[$i]['description'],
                $allListingInfo[$i]['address'],
                $allListingInfo[$i]['rooms'],
                $allListingInfo[$i]['date_from'],
                $allListingInfo[$i]['date_until'],
                $allListingInfo[$i]['price'],
                $allListingInfo[$i]['photo']
            );
            $date1 = new DateTime($allListingInfo[$i]['date_until']);
            $date2 = date("Y-m-d");
            $date3 = new DateTime($date2);
            if ($date1 < $date3) {
                $error[] = $allListingInfo[$i]['id'];
            }
        }
        return new View('Website/main', [
            'name' => $_SESSION['current_user']['name'] ?? '',
            'info' => $everything ?? '',
            'error' => $error
        ]);
    }

    public function createListing(): View
    {
        return new View('Users/listing', [
            'name' => $_SESSION['current_user']['name']
        ]);
    }

    public function addListingToDb(): Redirect
    {
        $fileName = uniqid("", true) . "." . explode(".", $_FILES["photo"]["name"])[1];
        move_uploaded_file($_FILES["photo"]["tmp_name"], "photosOfApps/" . $fileName);

        Database::connection()
            ->insert('listings', [
                'poster_id' => $_SESSION['current_user']['user_id'],
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'address' => $_POST['address'],
                'rooms' => $_POST['rooms'],
                'date_from' => $_POST['date_from'],
                'date_until' => $_POST['date_until'],
                'price' => $_POST['price'],
                'photo' => $fileName
            ]);

        return new Redirect('/main');
    }

    public function edit(array $vars): View
    {
        return new View('Website/edit', [
            'listing' => ApartmentsController::extracted($vars['id']),
            'name' => $_SESSION['current_user']['name'] ?? ''
        ]);
    }

    public function update(array $vars): Redirect
    {
        $fileName = uniqid("", true) . "." . explode(".", $_FILES["photo"]["name"])[1];
        move_uploaded_file($_FILES["photo"]["tmp_name"], "photosOfApps/" . $fileName);

        Database::connection()->update('listings', [
            'poster_id' => $_SESSION['current_user']['user_id'],
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'address' => $_POST['address'],
            'rooms' => $_POST['rooms'],
            'date_from' => $_POST['date_from'],
            'date_until' => $_POST['date_until'],
            'price' => $_POST['price'],
            'photo' => $fileName,
        ], ['id' => (int)$vars['id']]);

        return new Redirect('/main');
    }

    public function delete(array $vars): Redirect
    {
        $apartmentId = (int)$vars['id'];

        Database::connection()
            ->delete('listings', [
                'id' => $apartmentId
            ]);

        return new Redirect('/main');
    }
}