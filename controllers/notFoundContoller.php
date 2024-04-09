<?php 
class notFoundContoller extends Controller {

    public function index(){
        $data = array();

        $this->loadView('404', $data);
    }

}