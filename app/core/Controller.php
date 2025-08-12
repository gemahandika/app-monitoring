<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class Controller
{
    public function view($view, $data = [])
    {
        extract($data); // <-- ini akan membuat semua key di $data jadi variabel
        require_once '../app/views/' . $view . '.php';
    }

    public function model($model)
    {
        require_once '../app/models/' . $model . '.php';
        return new $model;
    }
}
