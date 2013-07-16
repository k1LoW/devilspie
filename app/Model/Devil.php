<?php
App::uses('AppModel', 'Model');
class Devil extends AppModel {

    public $useTable = false;

    public $validate = array(
        'hostname' => array('notempty', 'required'),
        'rootpass' => array('notempty', 'required'),
    );

}