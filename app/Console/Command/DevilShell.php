<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('Controller', 'Controller');
class DevilShell extends AppShell {

    private $target;

    /**
     * initialize
     *
     */
    public function initialize(){
        parent::initialize();
        $this->Controller = new Controller();
    }

    /**
     * _welcome
     *
     */
    protected function _welcome(){
        $this->hr();
        $this->out();
        $this->out(__d('devilspie', '<info> ####### Devil\'s Pie ####### </info>'));
        $incronCommand = APP . 'tmp/triggers IN_CREATE /usr/bin/php ' . APP . '../lib/Cake/Console/cake.php devil getsoul $@/$#';
        $this->out('<info> ' . $incronCommand . '</info>');
        $this->out();
        $this->hr();
    }

    /**
     * main
     *
     * @return
     */
    public function main() {
    }

    /**
     * getsoul
     *
     */
    public function getsoul(){
        if (empty($this->args[0])) {
            exit;
        }
        $triggerFile = new File(realpath($this->args[0]));
        if (!$triggerFile->exists()) {
            exit;
        }
        CakeLog::write(LOG_DEBUG, 'trigger file: ' . $triggerFile->pwd());
        if (preg_match('/empty$/', $triggerFile->name())) {
            exit;
        }
        $this->target = json_decode(trim($triggerFile->read()), true);
        $hostname = $this->target['hostname'];
        $rootpass = $this->target['rootpass'];

        $ansibleConfPath = TMP . $hostname . '_ansible_host.conf';
        $ansibleConf = new File($ansibleConfPath, true, 0666);
        $ansibleConf->delete();
        $ansibleConf->create();
        $ansibleConf->write($hostname);

        $logFilePath = LOGS . 'getsoul_' . $hostname . '.log';
        $log = new File($logFilePath, true, 0666);
        $log->delete();
        $log->create();

        $command = 'bash ' . APP . '/Console/shells/getsoul.sh ' . $hostname . ' ' . $rootpass . ' ' . APP;

        /*
          $descriptorspec = array(
          0 => array("pipe", "r"),  // stdin
          1 => array("file", $logFilePath, "a"),  // stdout
          2 => array("file", LOGS . 'error.log', "a") // stderr
          );
          $process = proc_open($command, $descriptorspec, $pipes, null, null);
          if (is_resource($process)) {
          fclose($pipes[0]);
          fclose($pipes[1]);
          fclose($pipes[2]);

          $return_value = proc_close($process);
          CakeLog::write(LOG_DEBUG, $return_value);
          }
        */

        exec($command, $output, $result);

        $output = implode("\n", $output) . "\n" . STDOUT_EOF;
        $log->write($output);

        $triggerFile->delete();

        $this->serverspec();
        $this->setserver();
        $this->setapp();
        $this->serverspec(true);
    }

    /**
     * serverspec
     *
     */
    public function serverspec($retest = false){
        CakeLog::write(LOG_DEBUG, 'setserver');
        if (empty($this->args[0]) && empty($this->target)) {
            exit;
        }
        if (empty($this->target)) {
            $triggerFile = new File(realpath($this->args[0]));
            if (!$triggerFile->exists()) {
                exit;
            }
            $this->target = json_decode(trim($triggerFile->read()), true);
            $triggerFile->delete();
        }
        $hostname = $this->target['hostname'];
        $application = $this->target['application'];

        // make spec dir
        $specDir = TMP . 'spec' . $hostname;
        $template = new Folder(APP . 'Console/serverspec/template');
        $template->copy($specDir);
        $hostnameDir = new Folder($specDir . '/spec/hostname');
        $hostnameDir->move($specDir . '/spec/' . $hostname);

        $this->Controller->set(array(
                'apppath' => APP,
                'hostname' => $hostname,
                'dbname' => $application,
            ));
        $view = new View($this->Controller, false);
        $view->layout = false;
        $spec_helper = $view->element('templates/serverspec_spec_helper');
        $file = new File($specDir . '/spec/spec_helper.rb', true, 0666);
        $file->delete();
        $file->create();
        $file->write($spec_helper);

        if ($retest) {
            $logFilePath = LOGS . 're_serverspec_' . $hostname . '.log';
        } else {
            $logFilePath = LOGS . 'serverspec_' . $hostname . '.log';
        }
        $log = new File($logFilePath, true, 0666);
        $log->delete();
        $log->create();

        $command = 'cd ' . $specDir . ';export TERM=xterm;export PATH=$PATH:$HOME/.rvm/bin;source ~/.rvm/scripts/rvm;rvm use 1.9.3;rake spec 2>&1';
        $log->write('prompt ' . $command . "\n");
        CakeLog::write(LOG_DEBUG, $command);
        exec($command, $output, $result);

        $output = implode("\n", $output) . "\n";
        $log->write($output);
        $log->write("\n" . STDOUT_EOF);
    }

    /**
     * setapp
     *
     */
    public function setapp(){
        CakeLog::write(LOG_DEBUG, 'setapp');
        if (empty($this->args[0]) && empty($this->target)) {
            exit;
        }
        if (empty($this->target)) {
            $triggerFile = new File(realpath($this->args[0]));
            if (!$triggerFile->exists()) {
                exit;
            }
            $this->target = json_decode(trim($triggerFile->read()), true);
            $triggerFile->delete();
        }

        $hostname = $this->target['hostname'];
        $application = $this->target['application'];
        $privateKeyPath = APP . 'Config/devil_rsa';

        $logFilePath = LOGS . 'setapp_' . $hostname . '.log';
        $log = new File($logFilePath, true, 0666);
        $log->delete();
        $log->create();

        $capDir = TMP . 'cap' . $hostname;
        $template = new Folder(APP . 'Console/capistrano');
        $template->copy($capDir);

        $this->Controller->set(array(
                'apppath' => APP,
                'hostname' => $hostname,
                'dbname' => $application,
            ));
        $view = new View($this->Controller, false);
        $view->layout = false;
        $cap = $view->element('templates/capistrano_' . $application);
        $capPath = $capDir . '/config/deploy/' . $application . '.rb';
        $file = new File($capPath, true, 0666);
        $file->delete();
        $file->create();
        $file->write($cap);

        $command = 'cd ' . $capDir . ';export TERM=xterm;export PATH=$PATH:$HOME/.rvm/bin:$HOME/.rvm/gems/ruby-1.9.3-p448/bin/;source ~/.rvm/scripts/rvm;rvm use 1.9.3;cap ' . $application . ' deploy:setup 2>&1';
        $log->write('prompt ' . $command . "\n");
        CakeLog::write(LOG_DEBUG, $command);
        exec($command, $output, $result);

        $output = implode("\n", $output) . "\n";
        $log->write($output);

        $command = 'cd ' . $capDir . ';export TERM=xterm;export PATH=$PATH:$HOME/.rvm/bin:$HOME/.rvm/gems/ruby-1.9.3-p448/bin/;source ~/.rvm/scripts/rvm;rvm use 1.9.3;cap ' . $application . ' deploy 2>&1';
        $log->write('prompt ' . $command . "\n");
        CakeLog::write(LOG_DEBUG, $command);
        exec($command, $output, $result);

        $output = implode("\n", $output) . "\n";
        $log->write($output);

        $log->write("\n" . STDOUT_EOF);
    }

    /**
     * setserver
     *
     */
    public function setserver(){
        CakeLog::write(LOG_DEBUG, 'setserver');
        if (empty($this->args[0]) && empty($this->target)) {
            exit;
        }
        if (empty($this->target)) {
            $triggerFile = new File(realpath($this->args[0]));
            if (!$triggerFile->exists()) {
                exit;
            }
            $this->target = json_decode(trim($triggerFile->read()), true);
            $triggerFile->delete();
        }

        $hostname = $this->target['hostname'];
        $application = $this->target['application'];
        $privateKeyPath = APP . 'Config/devil_rsa';
        $ansibleConfPath = TMP . $hostname . '_ansible_host.conf';

        $application_playbooks = Configure::read('Devil.application_playbooks');
        $playbooks = $application_playbooks[$application];

        $logFilePath = LOGS . 'setserver_' . $hostname . '.log';
        $log = new File($logFilePath, true, 0666);
        $log->delete();
        $log->create();

        $this->Controller->set(array(
                'apppath' => APP,
                'hostname' => $hostname,
                'dbname' => $application,
            ));
        $view = new View($this->Controller, false);
        $view->layout = false;
        foreach ($playbooks as $key => $playbook) {
            $yml = $view->element($playbook);
            CakeLog::write(LOG_DEBUG, $yml);
            $playbookPath = TMP . $hostname . '_' . $key . '.yml';
            $file = new File($playbookPath, true, 0666);
            $file->delete();
            $file->create();
            $file->write($yml);

            $command = 'ansible-playbook ' . $playbookPath . ' -i ' . $ansibleConfPath . ' --private-key=' . $privateKeyPath;
            $log->write('prompt ' . $command . "\n");
            CakeLog::write(LOG_DEBUG, $command);
            exec($command, $output, $result);

            $output = implode("\n", $output) . "\n";
            CakeLog::write(LOG_DEBUG, $output);
            $log->write($output);
        }
        $log->write("\n" . STDOUT_EOF);
    }
}