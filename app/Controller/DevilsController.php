<?php
App::uses('AppController', 'Controller');
class DevilsController extends AppController {

    /**
     * top
     *
     */
    public function top(){
        $this->Transition->clearData();
        $this->Transition->checkData('sold');
    }

    /**
     * sold
     *
     */
    public function sold(){
        $this->Transition->checkPrev(
            array(
                'top'
            )
        );
        $mergedData = $this->Transition->mergedData();
        file_put_contents(APP . 'tmp/triggers/' . $mergedData['Devil']['hostname'], json_encode($mergedData['Devil']));
        $this->set(compact('mergedData'));
    }

    /**
     * console
     *
     * @param $type = 'getsoul'
     */
    public function console($type = 'getsoul'){
        $this->layout = false;
        $this->autoRender = false;
        $mergedData = $this->Transition->mergedData();
        $logFilePath = LOGS . $type . '_' . $mergedData['Devil']['hostname'] . '.log';
        $log = new File($logFilePath);
        if (!$log->exists()) {
            return json_encode(array('result' => 'nofile'));
        }
        $output = $log->read();
        // prompt coloring
        $output = preg_replace('/(prompt|spawn)/', '<span class="prompt">[devil@devilspie]$</span>', $output);
        // serperspec coloring
        $output = preg_replace('/(Failures:)/', '<span class="failure">\1</span>', $output);
        $output = preg_replace('/([0-9]*[1-9] failures)/', '<span class="failure">\1</span>', $output);
        if (preg_match('/'. STDOUT_EOF . '/', $output)) {
            $log->delete();
            return json_encode(array(
                'result' => 'ok',
                'output' => utf8_encode($output)
                ));
        }
        return json_encode(array(
                'result' => 'loading',
                'output' => utf8_encode($output)
            ));
    }
}