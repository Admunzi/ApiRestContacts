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
                    $response = $this->getContactFromRequest($this->userId);
                } else {
                    $response = $this->getAllContactsFromRequest();
                };
                break;
            case 'POST':
                $response = $this->createContactsFromRequest();
                break;            
            case 'PUT':
                $response = $this->updateContactFromRequest($this->userId);
                break;            
            case 'DELETE':
                $response = $this->deleteContactsFromRequest($this->userId);
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

    private function getContactFromRequest($id){
        $result = $this->contacts->get($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function updateContactFromRequest($id){
        $result = $this->contacts->get($id);
        if (! $result) {
            return $this->notFoundResponse();
        }        
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        $input += ["id" => $id];
        $this->contacts->edit($input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function deleteContactsFromRequest($id){
        $result = $this->contacts->delete($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getAllContactsFromRequest(){
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
        $this->contacts->set($input);
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