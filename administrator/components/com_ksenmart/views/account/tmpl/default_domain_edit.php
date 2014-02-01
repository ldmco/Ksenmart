<div class="form" id="domain_edit">
    <div class="row clearfix">
        <label class="inputname">NS1</label>
        <input type="text" name="ns0" class="inputbox" required="true" value="<?php echo $this->domain_info->ns0; ?>" />
    </div>
    <div class="row clearfix">
        <label class="inputname">NS2</label>
        <input type="text" name="ns1" class="inputbox" required="true" value="<?php echo $this->domain_info->ns1; ?>" />
    </div>
    <div class="row clearfix">
        <label class="inputname">NS3</label>
        <input type="text" name="ns2" class="inputbox" value="<?php echo $this->domain_info->ns2; ?>" />
    </div>
    <div class="row clearfix">
        <label class="inputname">NS4</label>
        <input type="text" name="ns3" class="inputbox" value="<?php echo $this->domain_info->ns3; ?>" />
    </div>
    <div class="row clearfix">
        <label class="inputname">NS4</label>
        <select class="sel" onchange="" name="autoperiod">
            <option value=""<?php echo empty($this->domain_info->autoperiod)?' selected="true"':''; ?>>Отключено</option>
            <option value="<?php echo $this->period_id; ?>"<?php echo empty($this->domain_info->autoperiod)?'':' selected="true"'; ?>>1 year</option>
        </select>
    </div>
</div>
<script>
    jQuery(document).ready(function(){
        
    	var params = {
    			changedEl: ".sel", 
    		} 
    	cuSel(params);
        
        var current_popup   = jQuery('.popup.domain_edit');
        var form            = current_popup.find('#domain_edit');
        
        current_popup.parent().on('submit', function(e){

            e.preventDefault();

            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&task=account.domainEdit';
            
            var elid        = <?php echo $this->domain_info->elid; ?>;
            var ns0         = form.find('input[name="ns0"]').val();
            var ns1         = form.find('input[name="ns1"]').val();
            var ns2         = form.find('input[name="ns2"]').val();
            var ns3         = form.find('input[name="ns3"]').val();
            var autoperiod  = form.find('[name="autoperiod"]').val();

            var data = {
                    elid: elid, 
                    ns0: ns0,
                    ns1: ns1, 
                    ns2: ns2,
                    ns3: ns3,
                    autoperiod: autoperiod
            };

            jQuery.ajax({
                type: "POST",
    			url:href,
                data:data,
                dataType: 'json',
    			success: function(data){
    			    if(data.result == 'OK'){
                        closePopup(form);
                        return true;
    			    }else{
                        createPopupNotice(data.error.msg, '.domain_edit');
    			    }
    			}
    		});
        });
        
    });
</script>