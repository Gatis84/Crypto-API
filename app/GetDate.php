<?php
namespace App;

class GetDate implements Handler

{
    public function getData()
    {
        return "Local time: " . date('Y-m-d H:m:s');

    }
}