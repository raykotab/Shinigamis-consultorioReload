<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Coder;
use phpDocumentor\Reflection\Location; // QUE ES ESTO?

class ApiCodersController
{

    public function __construct()
    {
        if (isset($_GET) && isset($_GET["action"]) && ($_GET["action"] == "create")) {
            $this->create();
            return;
        }

        if (isset($_GET) && isset($_GET["action"]) && ($_GET["action"] == "store")) {
            $this->store($_POST);
            return;
        }

        if (isset($_GET) && isset($_GET["action"]) && ($_GET["action"] == "edit")) {
            $this->edit($_GET["id"]);
            return;
        }
        
        if (isset($_GET) && isset($_GET["action"]) && ($_GET["action"] == "update")) {
            $this->update($_POST, $_GET["id"]);
            return;
        }

        if (isset($_GET) && isset($_GET["action"]) && ($_GET["action"] == "delete")) {

            $this->delete($_GET["id"]);
            return;
        }

        $this->index();
       
    }

    public function index(): void
    {
        $codersList = Coder::all();
                
        $newCodersList =[];

        foreach ($codersList as $coder) {
            
            $newEntry = [

            "id"=>$coder->getId(), 
            "name"=>$coder->getName(), 
            "subject"=>$coder->getSubject(),
            "created"=>$coder->getCreatedAt()
            ];
            array_push($newCodersList, $newEntry);
        }
       
        echo json_encode($newCodersList);

        // new View("CoderList", [
        //     "students_db" => $codersList,
        // ]);
    }

    public function create(array $request): void
    {   
        //new View("CreateCoder");
    }

    public function store(array $request): void
    {
        $newCoder = new Coder($request["name"], $request["subject"]);
        $newCoder->save();
        $lastCoder = Coder::findLastCoder();
        var_dump($lastCoder);
        //$this->index();
    }

    public function delete($id)
    {
        $coderToDelete = Coder::findById($id);
        $deletedCoder = [
            "id"=>$coderToDelete->getId(),
            "name"=>$coderToDelete->getName(),
            "subject"=>$coderToDelete->getSibject(),
            "created_at"=>$coderToDelete->getCreatedAt()
        ];
        echo json_encode($deletedCoder);
        $coderToDelete->delete();
    }
    
    public function edit($id)
    {
        $coderToEdit = Coder::findById($id);
        new View("EditCoder", ["coder" => $coderToEdit]);
    }

    public function update(array $request, $id)
    {
        $coderToUpdate = Coder::findById($id);
        $coderToUpdate->rename($request["name"]);
        $coderToUpdate->editSubject($request["subject"]);
        $coderToUpdate->update();

        $this->index();
    }
}
