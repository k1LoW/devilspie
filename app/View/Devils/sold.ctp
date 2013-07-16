<?php echo $this->assign('bodyclass', 'dark'); ?>
<?php echo $this->Html->script('sold', false); ?>
<div class="row-fluid">
    <div class="span1">
    </div>
    <div class="span10 text-center">
        <?php echo $this->Html->image('devilspie_mono.png', array('url' => $baseUrl)); ?>
    </div>
    <div class="span1">
    </div>
</div>

<div class="row-fluid">
    <div class="span1">
    </div>
    <div class="span10">
        <div class="popover top">
            <div class="arrow"></div>
            <h3 class="popover-title">Popover top</h3>
            <div class="popover-content">
                <p>Sed posuere consectetur est at lobortis. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum.</p>
            </div>
        </div>
        <h3>> Devil break into your server</h3>
        <pre class="console console-getsoul"><div class="console-inner"></div><?php echo $this->Html->image('console_loading.gif', array()); ?></pre>

        <h3 class="failure">>> Red test</h3>
        <pre class="console console-serverspec hide"><div class="console-inner"></div><?php echo $this->Html->image('console_loading.gif', array()); ?></pre>

        <h3>>>> Server setting</h3>
        <pre class="console console-setserver hide"><div class="console-inner"></div><?php echo $this->Html->image('console_loading.gif', array()); ?></pre>

        <h3>>>>> Application setting</h3>
        <pre class="console console-setapp hide"><div class="console-inner"></div><?php echo $this->Html->image('console_loading.gif', array()); ?></pre>

        <h3 class="success">>>>>> Re test</h3>
        <pre class="console console-re_serverspec hide"><div class="console-inner"></div><?php echo $this->Html->image('console_loading.gif', array()); ?></pre>

        <h3>>>>>>> Application link</h3>
        <div class="console-link hide"><a target="_blank" href="http://<?php echo $mergedData['Devil']['hostname']; ?>/<?php echo $mergedData['Devil']['application']; ?>/current">http://<?php echo $mergedData['Devil']['hostname']; ?>/<?php echo $mergedData['Devil']['application']; ?>/current</a></div>
    </div>
    <div class="span1">
    </div>
</div>