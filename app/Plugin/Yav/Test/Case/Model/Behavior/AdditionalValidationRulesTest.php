<?php

class YavPost extends CakeTestModel {

    public $name = 'YavPost';
    public $actsAs = array(
        'Cakeplus.AddValidationRule',
        'Yav.AdditionalValidationRules'
    );

    public $validate = array();
}

class AdditionalValidationRulesTest extends CakeTestCase {

    public $fixtures = array('plugin.yav.yav_post');

    /**
     * setUp
     *
     */
    public function setUp(){
        $this->YavPost = ClassRegistry::init('YavPost');
    }

    /**
     * tearDown
     *
     */
    public function tearDown(){
        unset($this->YavPost);
    }

    /**
     * test_notEmptyWith
     *
     */
    public function test_notEmptyWith(){
        $this->YavPost->validate['not_empty_with1'] = array(
            'notEmptyWith' => array(
                'rule' => array('notEmptyWith', array('not_empty_with2', 'not_empty_with3'))
            ));
        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );

        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
                'not_empty_with3' => '空でない',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertTrue( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );
    }

   /**
     * test_notEmptyWithout
     *
     */
    public function test_notEmptyWithout(){
        $this->YavPost->validate['not_empty_with1'] = array(
            'notEmptyWithout' => array(
                'rule' => array('notEmptyWithout', array('not_empty_with2', 'not_empty_with3'))
            ));
        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertTrue( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );

        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
                'not_empty_with3' => '空でない',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );
    }

    /**
     * test_hiraganaOnly
     *
     * jpn: hiraganaOnlyだと全角スペースを許さない
     */
    public function test_hiraganaOnly(){
        $this->YavPost->validate['body'] = array(
            'hiraganaOnly' => array(
                'rule' => array('hiraganaOnly')
            ));
        $data = array(
            'YavPost' => array(
                'body'  =>  'ひらがな　と　すぺーす',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertTrue( array_key_exists('body' , $this->YavPost->validationErrors ) );
    }

    /**
     * test_hiraganaAndSpace
     *
     * jpn: hiraganaAndSpaceだと全角スペースを許す
     */
    public function test_hiraganaAndSpace(){
        $this->YavPost->validate['body'] = array(
            'hiraganaAndSpace' => array(
                'rule' => array('hiraganaAndSpace')
            ));
        $data = array(
            'YavPost' => array(
                'body'  =>  'ひらがな　と　すぺーす',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('body' , $this->YavPost->validationErrors ) );
    }

    /**
     * test_katakanaOnly
     *
     * jpn: katakanaOnlyだと全角スペースを許さない
     */
    public function test_katakanaOnly(){
        $this->YavPost->validate['body'] = array(
            'katakanaOnly' => array(
                'rule' => array('katakanaOnly')
            ));
        $data = array(
            'YavPost' => array(
                'body'  =>  'カタカナ　ト　スペース',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertTrue( array_key_exists('body' , $this->YavPost->validationErrors ) );
    }

    /**
     * test_katakanaAndSpace
     *
     * jpn: katakanaAndSpaceだと全角スペースを許す
     */
    public function test_katakanaAndSpace(){
        $this->YavPost->validate['body'] = array(
            'katakanaAndSpace' => array(
                'rule' => array('katakanaAndSpace')
            ));
        $data = array(
            'YavPost' => array(
                'body'  =>  'カタカナ　ト　スペース',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('body' , $this->YavPost->validationErrors ) );
    }
}