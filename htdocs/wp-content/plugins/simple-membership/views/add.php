<link rel='stylesheet' type='text/css' href='stylesheet.css'/>
<link rel='stylesheet' type='text/css' href='http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css'/>
<script type='text/javascript' src='script.js'></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
		
<div class="swpm-registration-widget-form">
    <form id="swpm-registration-form" name="swpm-registration-form" method="post" action="">
        <input type ="hidden" name="level_identifier" value="<?php echo $level_identifier ?>" />
        <table>
            <tr class="swpm-registration-username-row">
                <td><label style="color: red; font-size:20px;">*</label><label for="user_name"><?php echo SwpmUtils::_('Username') ?></label></td>
                <td><input type="text" id="user_name" class="validate[required,custom[noapostrophe],custom[SWPMUserName],minSize[4],ajax[ajaxUserCall]]" value="<?php echo $user_name; ?>" size="50" name="user_name" /></td>
            </tr>
            <tr class="swpm-registration-email-row">
                <td><label style="color: red; font-size:20px;">*</label><label for="email"><?php echo SwpmUtils::_('Email') ?></label></td>
                <td><input type="text" id="email" class="validate[required,custom[email],ajax[ajaxEmailCall]]" value="<?php echo $email; ?>" size="50" name="email" /></td>
            </tr>
            <tr class="swpm-registration-password-row">
                <td><label style="color: red; font-size:20px;">*</label><label for="password"><?php echo SwpmUtils::_('Password') ?></label></td>
                <td><input type="password" autocomplete="off" id="password" value="" size="50" name="password" /></td>
            </tr>
            <tr class="swpm-registration-password-retype-row">
                <td><label style="color: red; font-size:20px;">*</label><label for="password_re"><?php echo SwpmUtils::_('Repeat Password') ?></label></td>
                <td><input type="password" autocomplete="off" id="password_re" value="" size="50" name="password_re" /></td>
            </tr>
			
			<tr>
				<td><label style="color: red; font-size:20px;">*</label><label for="user_name">Aliment</label></td>
				<td>
					<select id="first_name" name="first_name" style="width:106px;" class="validate[required]">
						<option disabled selected hidden>-Select-</option>
						<option value="Alcohol Abuse Support Group">Alcohol Abuse</option>
						<option value="Cancer Support Group">Cancer</option>
						<option value="Diabetes Support Group">Diabetes</option>
						<option value="Drug Abuse Support Group">Drug Abuse</option>
						<option value="Obesity Support Group">Obesity</option>
					</select>
				</td>
			</tr>
			
            <tr>
				<td><label for="user_name">Gender</label></td>
				<td>
					<select id="gender" name="gender" style="width:106px;">
						<option value="0">-Select-</option>
						<option value="male">male</option>
						<option value="female">female</option>
					</select>
				</td>
			</tr>
			
			<tr>
				<td><label for="user_name">Date of Birth</label></td>
				<td>
					<input type="text" id="datepicker" style="width:120px;" placeholder="Select Date...">
				</td>
			</tr>
        </table>        
        
        <div class="swpm-before-registration-submit-section" align="center"><?php echo apply_filters('swpm_before_registration_submit_button', ''); ?></div>
        
        <div class="swpm-registration-submit-section" align="center">
            <input type="submit" value="<?php echo SwpmUtils::_('Register') ?>" class="swpm-registration-submit" name="swpm_registration_submit" />
        </div>
        
        <input type="hidden" name="action" value="custom_posts" />
        
    </form>
</div>
<script>
    jQuery(document).ready(function ($) {
        $.validationEngineLanguage.allRules['ajaxUserCall']['url'] = '<?php echo admin_url('admin-ajax.php'); ?>';
        $.validationEngineLanguage.allRules['ajaxEmailCall']['url'] = '<?php echo admin_url('admin-ajax.php'); ?>';
        $.validationEngineLanguage.allRules['ajaxEmailCall']['extraData'] = '&action=swpm_validate_email&member_id=<?php echo filter_input(INPUT_GET, 'member_id'); ?>';
        $("#swpm-registration-form").validationEngine('attach');
		
		$( "#datepicker" ).datepicker();
    });

</script>
