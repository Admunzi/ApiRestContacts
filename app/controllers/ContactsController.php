<?php
namespace App\Controllers;

use App\Models\Contact;

class ContactsController{
    private $requestMethod;
    private $userId;
    private $contacts;
    
    public function __construct($requestMethod, $userId){
        $this->requestMethod = $requestMethod;
        $this->userId = $userId;
        $this->contacts = contact::getInstancia();
    }
    
    public function processRequest(){
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->userId) {
                    $response = $this->getContacts($this->userId);
                } else {
                    $response = $this->getAllContacts();
                };
                break;
            case 'POST':
                $response = $this->createContactsFromRequest();
                break;            
            case 'PUT':
                $response = $this->updateContactFromRequest($this->userId);
                break;            
            case 'DELETE':
                $response = $this->deleteContacts($this->userId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getContacts($id){
        $result = $this->contacts->get($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function deleteContacts($id){
        $result = $this->contacts->delete($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getAllContacts(){
        $result = $this->contacts->getAll();
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createContactsFromRequest(){
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        // if (! $this->validateContacto($input)) {
        //     return $this->unprocessableEntityResponse();
        // }
        $this->contacts->set($input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function updateContactFromRequest($id){
        $result = $this->contacts->get($id);

        if (! $result) {
            return $this->notFoundResponse();
        }        
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        array_push($input,$id);
        // if (! $this->validateContacto($input)) {
        //     return $this->unprocessableEntityResponse();
        // }
        $this->contacts->edit($input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }



    private function notFoundResponse(){
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }


}
?>