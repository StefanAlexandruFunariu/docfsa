<footer>
    <div class="pull-right">
        <?php if(isset($this->gsms_setting->footer) && !empty($this->gsms_setting->footer)){ ?>                            
            <?php echo $this->gsms_setting->footer; ?>                
        <?php }else{ ?>  
            ©<?php date('Y'); ?> 
        <?php } ?>
    </div>
    <div class="clearfix"></div>
</footer>