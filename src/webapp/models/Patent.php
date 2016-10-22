<?php

namespace tdt4237\webapp\models;

class Patent
{
    protected $patentId = null;
    protected $title;
    protected $file;
    protected $company;
    protected $description;
    protected $date;

    function __construct($company, $title, $description, $date, $file)
    {
        $this->company = $company;
        $this->title = $title;
        $this->description = $description;
        $this->date = $date;
        $this->file = $file;
    }

    public function getPatentId() {
        return $this->patentId;

    }

    public function setPatentId($patentId) {
        $this->patentId = $patentId;
        return $this;
    }

    public function getCompany() {
        return $this->company;
    }

    public function setCompany($company) {
        $this->company = $company;
        return $this;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getFile(){
        return $this->file;
    }

    public function setFile($file){
        $this->file = $file;
    }
}