<?php

namespace App\Traits;

trait HandleResponse
{
    public static function encrypt_decrypt($action, $string)
    {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = 'PGORM COVID-19';
        $secret_iv = 'mis-dev';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    public static function getAllowedStatus()
    {

        $user = auth()->user()->roles()->get();
        $allStatusAvailable = [];

        foreach ($user as $key => $role) {

            $role_status = config('constants.role_status');

            $allStatusAvailable[] = $role_status[$role->title];
        }
        $uniqueStatus = array_unique(array_merge(...$allStatusAvailable));

        $listOfStatus = [];
        $status = config('constants.payroll_status');
       
        foreach ($uniqueStatus as $key => $value) {
            $listOfStatus[] = [
                "id" => $value,
                "status" => $status[$value]["approve_text"]
            ];
        }

        return $listOfStatus;
    }

    public static function fetchStatus()
    {
        $listOfStatus = [];
        $status = config('constants.payroll_status');
        foreach ($status as $key => $value) {
            $listOfStatus[] = [
                "id" => $value['id'],
                "status" => $value["approve_text"]
            ];
        }
        return $listOfStatus;
    }

    public static function getAllowedStatusApprove()
    {

        $user = auth()->user()->roles()->get();
        $allStatusAvailable = [];
        foreach ($user as $key => $role) {

            $role_status = config('constants.role_status_approve');
            $allStatusAvailable[] = $role_status[$role->title];
        }
        $uniqueStatus = array_unique(array_merge(...$allStatusAvailable));

        $listOfStatus = [];
        $status = config('constants.payroll_status');
        foreach ($uniqueStatus as $key => $value) {
            $listOfStatus[] = [
                "id" => $value,
                "status" => $status[$value]["approve_text"],
                "category" => $status[$value]["category"],
            ];
        }

        return $listOfStatus;
    }

    public static function getListStatus()
    {
        $user = auth()->user()->roles()->get();
        $allStatusAvailable = [];
        foreach ($user as $key => $role) {
            $role_status = config('constants.role_status');
            $allStatusAvailable[] = $role_status[$role->title];
        }
        $uniqueStatus = array_unique(array_merge(...$allStatusAvailable));
        return $uniqueStatus;
    }


    public static function approvedStatus()
    {
        $user = auth()->user()->roles()->get();
        $allStatusAvailable = [];
        foreach ($user as $key => $role) {
            $role_status = config('constants.role_status');
            $allStatusAvailable[] = $role_status[$role->title];
        }
        $uniqueStatus = array_unique(array_merge(...$allStatusAvailable));
        return $uniqueStatus;
    }



    public static function nextStatus($statusID)
    {
        $newStatus = 1;
        switch ($statusID) {
            case 1:
                $newStatus = 3;
                break;
            case 3:
                $newStatus = 4;
                break;
            case 4:
                $newStatus = 5;
                break;
            case 5:
                $newStatus = 6;
                break;
            case 6:
                $newStatus = 7;
                break;
            case 7:
                $newStatus = 8;
                break;
            case 8:
                $newStatus = 9;
                break;
            case 9:
                $newStatus = 10;
                break;
            case 11: // from opa incomplete to psu checked 
                $newStatus = 13;
                break;
            case 12:
                $newStatus = 14;
                break;
            case 13:
                $newStatus = 4;
                break;
            case 14:
                $newStatus = 5;
                break;
            case 16:
                $newStatus = 17;
                break;
            case 16:
                $newStatus = 17;
                break;
            case 17:
                $newStatus = 3;
                break;
            case 18:
                $newStatus = 3;
                break;
            case 19:
                $newStatus = 5;
                break;
            default:
                $newStatus = 1;
                break;
        }


        return $newStatus;
    }
}
