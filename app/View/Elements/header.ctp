<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <?php echo $this->Html->link('devil\'s pie', array(
              'controller' => 'devils',
              'action' => 'top'
              ), array('class' => 'brand')); ?>
            <ul class="nav">
                <li><?php echo $this->Html->link('Bootstrap2', array(
                'action' => 'bootstrap2'
                )); ?></li>
                <li><?php echo $this->Html->link('Bootstrap3', array(
                'action' => 'bootstrap3'
                )); ?></li>
            </ul>
        </div>
    </div>
</div>
