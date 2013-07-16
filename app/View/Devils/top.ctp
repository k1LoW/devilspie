<?php echo $this->Form->create('Devil', array('action' => 'top')); ?>
<div class="row-fluid">
    <div class="span4">
    </div>
    <div class="span4 text-center">
        <?php echo $this->Html->image('devilspie.png', array()); ?>
        <h3>>>> devil's pie <<<</h3>
    </div>
    <div class="span4">
    </div>
</div>
<div class="row-fluid">
    <div class="span4">
    </div>
    <div class="span4 text-center">
        <?php echo $this->Form->input('application', array('type' => 'select', 'options' => $applications, 'label' => false)); ?>
        <!-- Button to trigger modal -->
        <a href="#devilModal" role="button" class="btn btn-large" data-toggle="modal">Setup Server/Application</a>
    </div>
    <div class="span4">
    </div>
</div>
<!-- Modal -->
<div id="devilModal" class="dark modal hide fade" tabindex="-1" role="dialog" aria-labelledby="devilModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="devilModalLabel">Sell your soul...he he he</h3>
    </div>
    <div class="modal-body text-center">
        <?php echo $this->Form->input('hostname', array('type' => 'text', 'placeholder' => __('Hostname'), 'label' => false)); ?>
        <?php echo $this->Form->input('rootpass', array('type' => 'password', 'placeholder' => __('Root Password'), 'label' => false)); ?>
        <?php echo $this->Form->submit(__('Sell'), array('formnovalidate' => true, 'class' => 'btn btn-large')); ?>
    </div>
</div>
<?php echo $this->Form->end(); ?>
