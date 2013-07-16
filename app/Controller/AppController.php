<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package     app.Controller
 * @link        http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $components = array(
        'Session',
        'Security',
        'Paginator',
        'Transition.Transition',
        'Escape.Escape' => array('formDataEscape' => false),
    );

    public $helpers = array(
        'Session',
        'Html' => array('className' => 'BoostCake.BoostCakeHtml'),
        'Form' => array('className' => 'BoostCake.BoostCakeForm'),
        'Paginator' => array('className' => 'BoostCake.BoostCakePaginator'),
    );

    /**
     * beforeFilter
     *
     */
    public function beforeFilter(){
        parent::beforeFilter();
        Configure::write('Config.language', 'ja');

        // Transition
        $this->Transition->flashParams = array(
            'element' => 'alert',
            'params' => array(
                'plugin' => 'BoostCake',
                'class' => 'alert-error'
            ),
            'key' => 'flash',
        );
        $this->Transition->messages = array(
            'invalid' => __('Input Data was not able to pass validation. Please, try again.'),
            'prev'    => __('Session timed out.'),
        );
    }

    /**
     * beforeRender
     *
     */
    public function beforeRender(){
        parent::beforeRender();
        $baseUrl = Router::url('/');
        $this->set(array(
                'baseUrl' => $baseUrl,
                'applications' => Configure::read('Devil.applications')
            ));
    }
}
