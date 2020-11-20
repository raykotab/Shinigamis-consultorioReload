<?php


namespace App\Models;

use App\Database;

class Coder
{
    private ?int $id; 
    private string $name;
    private string $subject;
    private ?string $created_at;
    private $database;
    private $table = "students_db";

    public function __construct(string $name = '', string $subject = '', int $id = null, string $created_at = null)
    {
        $this->name = $name;
        $this->id = $id;
        $this->subject = $subject;
        $this->created_at = $created_at;

        if (!$this->database) {
            $this->database = new Database();
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function rename($name)
    {
        $this->name = $name;
    }

    public function editSubject($subject)
    {
        $this->subject = $subject;
    }

    public function save(): void
    {
        $this->database->mysql->query("INSERT INTO `{$this->table}` (`name`, `subject`) VALUES ('$this->name', '$this->subject');");
    }

    public static function findLastCoder(): Coder
    {
        $database = new Database();
        $query = $database->mysql->query("SELECT * FROM `students_db` WHERE id=(SELECT max(id) FROM `students_db`)");
        $result = $query->fetchAll();
        return new self($result[0]["name"], $result[0]["subject"], $result[0]["id"], $result[0]["created_at"]);
    }

    public function UpdateById($data, $id)
    {
        $this->database->mysql->query("UPDATE `students_db` SET `name` = '{$data["name"]}', `subject` = '{$data["subject"]}', WHERE `id` = {$id}"); 
    }

    public function Update()
    {
        $this->database->mysql->query("UPDATE `students_db` SET `name` =  '{$this->name}', `subject` = '{$this->subject}' WHERE `id` = {$this->id}");
    }

  }
