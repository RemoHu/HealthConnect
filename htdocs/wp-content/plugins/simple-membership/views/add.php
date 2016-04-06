<div class="swpm-registration-widget-form">
    <form id="swpm-registration-form" name="swpm-registration-form" method="post" action="">
        <input type ="hidden" name="level_identifier" value="<?php echo $level_identifier ?>" />
        <table>
            <tr class="swpm-registration-username-row">
                <td><label for="user_name"><?php echo SwpmUtils::_('Username') ?></label></td>
                <td><input type="text" id="user_name" class="validate[required,custom[noapostrophe],custom[SWPMUserName],minSize[4],ajax[ajaxUserCall]]" value="<?php echo $user_name; ?>" size="50" name="user_name" /></td>
            </tr>
            <tr class="swpm-registration-email-row">
                <td><label for="email"><?php echo SwpmUtils::_('Email') ?></label></td>
                <td><input type="text" id="email" class="validate[required,custom[email],ajax[ajaxEmailCall]]" value="<?php echo $email; ?>" size="50" name="email" /></td>
            </tr>
            <tr class="swpm-registration-password-row">
                <td><label for="password"><?php echo SwpmUtils::_('Password') ?></label></td>
                <td><input type="password" autocomplete="off" id="password" value="" size="50" name="password" /></td>
            </tr>
            <tr class="swpm-registration-password-retype-row">
                <td><label for="password_re"><?php echo SwpmUtils::_('Repeat Password') ?></label></td>
                <td><input type="password" autocomplete="off" id="password_re" value="" size="50" name="password_re" /></td>
            </tr>
            <tr>
				<td><label for="user_name">Gender</label></td>
				<td>
					<select id="gender" name="gender" style="width:106px;">
						<option>male</option>
						<option>female</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="user_name">Aliment</label></td>
				<td>
					<select id="first_name" name="first_name" style="width:106px;">
						<option value="Alcohol Abuse">Alcohol Abuse</option>
						<option value="Cancer">Cancer</option>
						<option value="Diabetes">Diabetes</option>
						<option value="Drug Abuse">Drug Abuse</option>
						<option value="Obesity">Obesity</option>
					</select>
				</td>
			</tr>
			
			<tr>
				<td><label for="user_name">Age Group</label></td>
				<td>
					<select id="last_name" name="last_name" style="width:106px;">
						<option value="15-17">15-17</option>
						<option value="18-24">18-24</option>
						<option value="25-34">25-34</option>
						<option value="35-44">35-44</option>
						<option value="45-54">45-54</option>
						<option value="55-64">55-64</option>
						<option value="65-74">65-74</option>
						<option value=">75">>75</option>
					</select>
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
    });
</script>
