<?php

    /**
     * @package Web_Increment
     * @version 1.0.0
     */
    /*
    Plugin Name: Web Increment
    Plugin URI:
    Description: Enrich your Website with Chat Rooms and Private Chat, Video/Audio Calls, Games, Subscription Forms, and More!
    Author: Web Increment
    Version: 1.0.0
    Author URI: https://webincrement.com
    */
	
	defined('ABSPATH') or die('Bye bye');
	define('WINC_WEB_INCREMENT_PATH', plugin_dir_path(__FILE__));

    # menu
    function winc_webincrement_administrator_menu(){
        add_menu_page('Web Increment', 'Web Increment', 'manage_options', 'web-increment-menu', 'winc_add_webincrement_page_content', plugin_dir_url( __FILE__ ).'includes/images/WI0IconColor.png');
    }
    add_action('admin_menu', 'winc_webincrement_administrator_menu');

    # Add installer to page
    function winc_add_webincrement_installer(){
        $winc_webincrementAccessCode = file_exists(WINC_WEB_INCREMENT_PATH."includes/accesscode.txt");
        if($winc_webincrementAccessCode){
            $winc_webincrementFopen = fopen(WINC_WEB_INCREMENT_PATH."includes/accesscode.txt",'r+');
            $winc_webincrementFileContent = fread($winc_webincrementFopen,filesize(WINC_WEB_INCREMENT_PATH."includes/accesscode.txt"));
            fclose($winc_webincrementFopen);
            $winc_webincrementFileContent = json_decode($winc_webincrementFileContent);
            $winc_webincrementIdCode = $winc_webincrementFileContent->winc_webincrementIdCode;
            $winc_webincrementUrlInstaller ='https://api.webincrement.com/v1/'.$winc_webincrementIdCode.'.js';
            wp_register_script( 'installer.js', $winc_webincrementUrlInstaller, array('jquery'), '1' );
            wp_enqueue_script( 'installer.js' );
        }
    }
    add_action("wp_enqueue_scripts", "winc_add_webincrement_installer");

    //Create accesscode.txt
    if($_GET["winc_webincrementIdCode"]){
        $winc_webincrementIdCode = sanitize_text_field( $_GET["winc_webincrementIdCode"] );
        $webincrementFile = fopen(WINC_WEB_INCREMENT_PATH."includes/accesscode.txt", "w");
        fwrite($webincrementFile, '{"winc_webincrementIdCode":"'.$winc_webincrementIdCode.'"}');
        fclose($webincrementFile);
        //Delete get vars
        $winc_webincrementUrlTemp = remove_query_arg('winc_webincrementIdCode', false);
        //Reload view
        header('Location: '.$winc_webincrementUrlTemp);
    }

    //Delete accesscode.txt
    if($_GET["winc_webincrementDeleteInstaller"]){
        if(file_exists(WINC_WEB_INCREMENT_PATH."includes/accesscode.txt")){
            unlink(WINC_WEB_INCREMENT_PATH."includes/accesscode.txt");
            //Delete get vars
            $winc_webincrementUrlTemp = remove_query_arg('winc_webincrementDeleteInstaller', false);
            //Reload view
            header('Location: '.$winc_webincrementUrlTemp);
        }
    }

    function winc_add_webincrement_page_content(){
        $winc_webincrementAccessCode = file_exists(WINC_WEB_INCREMENT_PATH."includes/accesscode.txt");
        if($winc_webincrementAccessCode){
            ?>
                <div style="float: left; background: transparent; background-image: url(<?php echo plugin_dir_url( __FILE__ ).'includes/images/bg.png';?>); width: calc(100% - 20px); margin-top: 20px; padding-bottom: 30px; border-radius: 4px;">
                    <div id="webincrementSpinnerDiv">
                        <div style="position: absolute; top: 0; left: -20px; z-index: 1; background: #1e88e51f; width: calc(100% + 20px); height: 100%;"></div>
                        <div style="position: absolute; margin-left: calc(50% - 85px); margin-top: calc(40vh - 85px); z-index: 1;">
                            <div class="loader"></div>
                        </div>
                    </div>
                    <div class="leftContainer">
                        <div style="background: white; width: 100%; border-radius: 4px; -webkit-box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.1); box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.1);">
                            <div style="padding: 20px;" class="headerImagesContainer">
                                <img style="width: 40px;" alt="" src="<?php echo plugin_dir_url( __FILE__ ).'includes/images/wilogo.png';?>">
                                <img style="height: 35px;" src="<?php echo plugin_dir_url( __FILE__ ).'includes/images/WI0Colors0.png';?>" alt="">
                            </div>
                            <div style="padding: 20px; text-align: center;">
                                <img style="width: 100%; max-width: 500px;" alt="" src="<?php echo plugin_dir_url( __FILE__ ).'includes/images/bg2.png';?>">
                            </div>
                            <div style="text-align: center; padding: 40px; padding-top: 0; padding-bottom: 80px;" class="openWebIncrementControlPanelButtomContainer">
                                <h2>Well done!</h2>
                                <div style="font-size: 14px; padding-bottom: 30px;">You have successfully installed WebIncrement Community Chat, Games, and Apps on your Website.</div>
                                <a href="https://admin.webincrement.com" target="_blank" class="openWebIncrementControlPanelButtom">
                                    <div>
                                        <img style="height: 16px; margin-right: 5px; margin-top: 1px; position: absolute;" src="<?php echo plugin_dir_url( __FILE__ ).'includes/images/link.png';?>" alt="">
                                        <span style="margin-left: 20px;">Open WebIncrement Dashboard</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div>
                            <button name="winc_webincrementDeleteInstaller" style="left: 10px; margin-top: 20px; margin-bottom: 20px; height: 40px; cursor: pointer; color:white; background: #1e88e5; border: 1px solid #1e88e5; padding: 0.55rem 1.5rem; border-radius: 4px;" onclick="winc_webincrementDeleteInstaller()"><img style="height: 16px; margin-top: 1px; margin-right: 5px; float: left;" src="<?php echo plugin_dir_url( __FILE__ ).'includes/images/signOut.png';?>" alt=""> Unlink your account</button>
                        </div>
                    </div>

                    <div class="rightContainer">
                        <center>
                            <h3 style="text-align: left; line-height: 21px; font-weight: lighter; width: calc(80% - 20px);"><b>The perfect solution to enrich your Website: We provide Chat Rooms and Private Chat, Video/Audio Calls, Games, Subscription Forms, and More!</b></h3>
                            <img style="width: calc(80% - 20px); margin-top: 5px;" alt="" src="<?php echo plugin_dir_url( __FILE__ ).'includes/images/example.gif';?>">
                            <h3 style="text-align: left; line-height: 21px; font-weight: lighter; width: calc(80% - 20px);"><b>Appearance and Settings:</b> Use the <a href="https://admin.webincrement.com" target="_blank">Dashboard</a> to easily customize the Appearance and Settings of Chat, Games, and Apps.</h3>
                        </center>
                    </div>

                </div>
                <script>
                    function winc_webincrementDeleteInstaller(){
                        var winc_webincrementDeleteInstallerCondition = confirm("Do you want to unlink your WebIncrement account?");
                        if (winc_webincrementDeleteInstallerCondition){
                            var url = new URL(window.location.href);
                            url.searchParams.set('winc_webincrementDeleteInstaller','winc_webincrementDeleteInstaller');
                            window.location.href = url.href;
                        }
                    }
                </script>
            <?php
        }else{
            ?>
                <div style="float: left; background: transparent; background-image: url(<?php echo plugin_dir_url( __FILE__ ).'includes/images/bg.png';?>); width: calc(100% - 20px); margin-top: 20px; padding-bottom: 60px; border-radius: 4px;">
                    <div id="webincrementSpinnerDiv">
                        <div style="position: absolute; top: 0; left: -20px; z-index: 1; background: #1e88e51f; width: calc(100% + 20px); height: 100%;"></div>
                        <div style="position: absolute; margin-left: calc(50% - 85px); margin-top: calc(40vh - 85px); z-index: 1;">
                            <div class="loader"></div>
                        </div>
                    </div>
                    <div>
                        <div class="leftContainerLogin" style="padding:10px;">
                            <center>
                                <div style="padding: 20px;" class="headerImagesContainerLogin">
                                    <img class="icon" style="height: 120px; display: -webkit-inline-flex; -webkit-box-orient: vertical; -webkit-box-direction: normal; -webkit-flex-direction: column; -webkit-box-pack: center; -webkit-flex-pack: center; -webkit-justify-content: center; -webkit-flex-align: center; -webkit-align-items: center; vertical-align: middle;" alt="" src="<?php echo plugin_dir_url( __FILE__ ).'includes/images/wilogo3.png';?>">
                                    <img class="name" style="height: 35px; display: -webkit-inline-flex; -webkit-box-orient: vertical; -webkit-box-direction: normal; -webkit-flex-direction: column; -webkit-box-pack: center; -webkit-flex-pack: center; -webkit-justify-content: center; -webkit-flex-align: center; -webkit-align-items: center; vertical-align: middle; margin-left: -28px; margin-top: 10px;" src="<?php echo plugin_dir_url( __FILE__ ).'includes/images/WI0Colors0.png';?>" alt="">
                                </div>
								
								<div id="webincrement_login_section" class="card" style="border-radius: 4px; padding: 1.5em 2em; margin-top: 10px; text-align:left; max-width:420px !important; width:100%; -webkit-box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.1); box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.1);">
                                    <form class="form" onsubmit="winc_webincrementLogin(); return false">
                                        <img style="width: 25px; float: left; padding-right: 10px; margin-top: -1px;" alt="" src="<?php echo plugin_dir_url( __FILE__ ).'includes/images/signIn.png';?>">
                                        <h2 style="margin-top: 0; padding-bottom: 1em; border-bottom: 1px solid #ccd0d4; font-size: 1.5em;">To install</h2>
                                        <div class="form-group" style="margin-top: 5px;">
											<h2 style="text-align: center; margin-top: 0; padding-bottom: 5px; font-size: 1.5em;">Log in</h2>
                                            <div class="input-group">
                                                <input type="text" id="webincrement_login_email" class="form-control" placeholder="Your Email" style="height: 38px; width: 100%; padding: 6px 12px 6px 12px;">
												<div id="webincrement_login_error_email" style="border-radius: 4px; background-color: #fc4b6c; color: white; padding: 6px 8px; margin-top: 4px; display: none;">
                                                    <div style="margin-top: -4px; float: right; font-weight: bold; cursor: pointer;" onclick="winc_webincrementCloseLoginErrorEmail();">x</div>
                                                    <div style="margin-top: -3px;">Required</div>
                                                </div>
                                            </div>
											<br>											
                                            <div class="input-group">
                                                <input type="password" id="webincrement_login_password" class="form-control" placeholder="Your Password" style="height: 38px; width: 100%; padding: 6px 12px 6px 12px;">
                                                <div id="webincrement_login_error_password" style="border-radius: 4px; background-color: #fc4b6c; color: white; padding: 6px 8px; margin-top: 4px; display: none;">
                                                    <div style="margin-top: -4px; float: right; font-weight: bold; cursor: pointer;" onclick="winc_webincrementCloseLoginErrorPassword();">x</div>
                                                    <div style="margin-top: -3px;">Required</div>
                                                </div>
                                            </div>											
                                        </div>
                                        <div class="text-left">
                                            <div>
                                                <a href="https://admin.webincrement.com/#/reset/request" target="_blank" style="color: #06f; cursor: pointer; text-align: right; display: block">Forgot password?</a>
                                            </div>
                                        </div>		
										<br>										
                                        <div class="text-left">
                                            <button type="submit" id="webincrement_installation_code_login_submit_button" style="font-weight: bold; cursor: pointer; color:white; margin-top: 15px; background: #1e88e5; border: 1px solid #1e88e5; padding: 0.5rem 1rem 0.7rem 1rem; border-radius: 4px; margin-bottom: 3px;"><img style="height: 16px; margin-top: 1px; margin-right: 5px; float: left;" src="<?php echo plugin_dir_url( __FILE__ ).'includes/images/check.png';?>" alt=""> Install</button>
                                            <div class="classGoToInstallationCode">
                                                <span onclick="winc_webincrement_show_register()" id="webincrement_login_link" style="color: #06f; cursor: pointer;">Create an Account</span>
                                            </div>
                                        </div>
                                    </form>
                                </div>								
								
								
                                <div id="webincrement_register_section" class="card" style="border-radius: 4px; padding: 1.5em 2em; margin-top: 10px; text-align:left; max-width:420px !important; width:100%; -webkit-box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.1); box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.1);">
                                    <form class="form" onsubmit="winc_webincrementRegister(); return false">
                                        <img style="width: 25px; float: left; padding-right: 10px; margin-top: -1px;" alt="" src="<?php echo plugin_dir_url( __FILE__ ).'includes/images/signIn.png';?>">
                                        <h2 style="margin-top: 0; padding-bottom: 1em; border-bottom: 1px solid #ccd0d4; font-size: 1.5em;">To install</h2>
                                        <div class="form-group" style="margin-top: 5px;">
											<h2 style="text-align: center; margin-top: 0; padding-bottom: 5px; font-size: 1.5em;">Create an Account</h2>
                                            <div class="input-group">
                                                <input type="text" id="webincrement_register_name" class="form-control" placeholder="Your Name" style="height: 38px; width: 100%; padding: 6px 12px 6px 12px;">
                                                <div id="webincrement_register_error_name" style="border-radius: 4px; background-color: #fc4b6c; color: white; padding: 6px 8px; margin-top: 4px; display: none;">
                                                    <div style="margin-top: -4px; float: right; font-weight: bold; cursor: pointer;" onclick="winc_webincrementCloseRegisterErrorName();">x</div>
                                                    <div style="margin-top: -3px;">Required</div>
                                                </div>
                                            </div>
											<br>
                                            <div class="input-group">
                                                <input type="text" id="webincrement_register_email" class="form-control" placeholder="Your Email" style="height: 38px; width: 100%; padding: 6px 12px 6px 12px;">
                                                <div id="webincrement_register_error_email" style="border-radius: 4px; background-color: #fc4b6c; color: white; padding: 6px 8px; margin-top: 4px; display: none;">
                                                    <div style="margin-top: -4px; float: right; font-weight: bold; cursor: pointer;" onclick="winc_webincrementCloseRegisterErrorEmail();">x</div>
                                                    <div style="margin-top: -3px;">Required</div>
                                                </div>
                                            </div>
											<br>											
                                            <div class="input-group">
                                                <input type="password" id="webincrement_register_password" class="form-control" placeholder="Create a Password" style="height: 38px; width: 100%; padding: 6px 12px 6px 12px;">
                                                <div id="webincrement_register_error_password" style="border-radius: 4px; background-color: #fc4b6c; color: white; padding: 6px 8px; margin-top: 4px; display: none;">
                                                    <div style="margin-top: -4px; float: right; font-weight: bold; cursor: pointer;" onclick="winc_webincrementCloseRegisterErrorPassword();">x</div>
                                                    <div style="margin-top: -3px;">Required</div>
                                                </div>
                                            </div>
											<br>
                                            <div class="input-group">
                                                <input type="password" id="webincrement_register_password2" class="form-control" placeholder="Confirm the Password" style="height: 38px; width: 100%; padding: 6px 12px 6px 12px;">
                                                <div id="webincrement_register_error_password2" style="border-radius: 4px; background-color: #fc4b6c; color: white; padding: 6px 8px; margin-top: 4px; display: none;">
                                                    <div style="margin-top: -4px; float: right; font-weight: bold; cursor: pointer;" onclick="winc_webincrementCloseRegisterErrorPassword2();">x</div>
                                                    <div style="margin-top: -3px;">Required</div>
                                                </div>
                                            </div>											
											<br>
											<div class="input-group">
												<input type="checkbox" id="webincrement_register_agree" class="form-control" name="webincrement_register_agree" value="">
												<label for="checkbox-signup2"> I agree the <a target="_blank" href="https://www.webincrement.com/terms">Terms of Use</a> and <a target="_blank" href="https://www.webincrement.com/privacy">Privacy Policy</a></label>
												<div id="webincrement_register_error_agree" style="border-radius: 4px; background-color: #fc4b6c; color: white; padding: 6px 8px; margin-top: 4px; display: none;">
                                                    <div style="margin-top: -4px; float: right; font-weight: bold; cursor: pointer;" onclick="winc_webincrementCloseRegisterErrorAgree();">x</div>
                                                    <div style="margin-top: -3px;">Required</div>
                                                </div>
											</div>											
                                        </div>
										<br>
                                        <div class="text-left">
                                            <button type="submit" id="webincrement_installation_code_submit_button" style="font-weight: bold; cursor: pointer; color:white; margin-top: 15px; background: #1e88e5; border: 1px solid #1e88e5; padding: 0.5rem 1rem 0.7rem 1rem; border-radius: 4px; margin-bottom: 3px;"><img style="height: 16px; margin-top: 1px; margin-right: 5px; float: left;" src="<?php echo plugin_dir_url( __FILE__ ).'includes/images/check.png';?>" alt=""> Install</button>
                                            <div class="classGoToInstallationCode">
                                                Already have an account? <span onclick="winc_webincrement_show_login()" id="webincrement_register_link" style="color: #06f; cursor: pointer;">Log in</span>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </center>
                        </div>
                        <div class="rightContainerLogin">
							<center>
								<h3 style="text-align: left; line-height: 21px; font-weight: lighter; width: calc(80% - 20px);"><b>The perfect solution to enrich your Website: We provide Chat Rooms and Private Chat, Video/Audio Calls, Games, Subscription Forms, and More!</b></h3>
								<img style="width: calc(80% - 20px); margin-top: 5px;" alt="" src="<?php echo plugin_dir_url( __FILE__ ).'includes/images/example.gif';?>">
								<h3 style="text-align: left; line-height: 21px; font-weight: lighter; width: calc(80% - 20px);"><b>Appearance and Settings:</b> Use the <a href="https://webincrement.com" target="_blank">Dashboard</a> to easily customize the Appearance and Settings of Chat, Games, and Apps.</h3>
							</center>
                        </div>
                    </div>
                </div>
                <script language='javascript'>
					document.getElementById("webincrement_login_section").style.display = "none";
				
					function winc_webincrement_show_login() {
						document.getElementById("webincrement_register_section").style.display = "none";
						document.getElementById("webincrement_login_section").style.display = "block";
					}
					
					function winc_webincrement_show_register() {
						document.getElementById("webincrement_login_section").style.display = "none";
						document.getElementById("webincrement_register_section").style.display = "block";
					}
					
					function winc_webincrementLogin() {
						if(winc_webincrementValidateLoginEmailField() && winc_webincrementValidateLoginPasswordField()){
                            document.getElementById("webincrementSpinnerDiv").style.display = "block";
							var webincrementLoginEmail = document.getElementById("webincrement_login_email").value;
							var webincrementLoginPassword = document.getElementById("webincrement_login_password").value;

							fetch("https://admin.webincrement.com/api/accountCodeFromEmailWordpress", {
								method: "POST",
								body: JSON.stringify({
									username: webincrementLoginEmail,
									password: webincrementLoginPassword
								}),
								headers: {
									"Content-type": "application/json; charset=UTF-8"
								}
							})
							.then(response => response.text())
							.then(webincrementInstallationCode => {
								winc_webincrementValidateInstallationCode(webincrementInstallationCode);
							});
						}
					}
				
                    function winc_webincrementRegister(){
                        if(winc_webincrementValidateRegisterNameField() && winc_webincrementValidateRegisterEmailField() && winc_webincrementValidateRegisterPasswordField() && winc_webincrementValidateRegisterPassword2Field() && winc_webincrementValidateRegisterAgreeField()){
                            document.getElementById("webincrementSpinnerDiv").style.display = "block";
							
							var webincrementRegisterName = document.getElementById("webincrement_register_name").value;
							var webincrementRegisterEmail = document.getElementById("webincrement_register_email").value;
							var webincrementRegisterPassword = document.getElementById("webincrement_register_password").value;
							
							fetch("https://admin.webincrement.com/api/registerWordpress", {
								method: "POST",
								body: JSON.stringify({
									firstName: webincrementRegisterName,
									email: webincrementRegisterEmail,
									password: webincrementRegisterPassword
								}),
								headers: {
									"Content-type": "application/json; charset=UTF-8"
								}
							})
							.then(response => response.text())
							.then(webincrementInstallationCode => {
								winc_webincrementValidateInstallationCode(webincrementInstallationCode);
							});
                        }
                    }
					
					function winc_webincrementValidateInstallationCode(webincrementInstallationCode) {
								if(isNaN(webincrementInstallationCode)){
									if (webincrementInstallationCode === "error") {
										document.getElementById("webincrementSpinnerDiv").style.display = "none";
										alert('Unexpected error occurred.');
									} else {
										if(webincrementInstallationCode.includes("https://api.webincrement.com/v1/")){
											var webincrementInstallationCodeStart = webincrementInstallationCode.indexOf("v1/");
											var webincrementInstallationCodeEnd = webincrementInstallationCode.indexOf("\.js");
											if(webincrementInstallationCodeStart>0 && webincrementInstallationCodeEnd>0 && webincrementInstallationCodeStart<webincrementInstallationCodeEnd){
												webincrementInstallationCode = webincrementInstallationCode.substring(webincrementInstallationCodeStart + 3, webincrementInstallationCodeEnd);
												if(webincrementInstallationCode){
													winc_validateInstallationCodeOnTheWebincrementPlatform(webincrementInstallationCode);
												}else{
													winc_webincrementOpenInstallationCodeError();
												}
											}else{
												winc_webincrementOpenInstallationCodeError();
											}
										}else{
											winc_webincrementOpenInstallationCodeError();
										}
									}
								}else{
									winc_validateInstallationCodeOnTheWebincrementPlatform(webincrementInstallationCode);
								}
					}
					
                    function winc_validateInstallationCodeOnTheWebincrementPlatform(webincrementInstallationCode){
                        var webincrementXhttp = new XMLHttpRequest();
                        webincrementXhttp.open("GET", "https://app.webincrement.com/api/validateInstallationCode/"+webincrementInstallationCode, true);
                        webincrementXhttp.setRequestHeader("Content-Type", "application/json");
                        webincrementXhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                                var webincrementXhttpResponse = this.responseText;
                                if(webincrementXhttpResponse && (webincrementXhttpResponse === true || webincrementXhttpResponse === "true")){
                                    var webincrementUrl = new URL(window.location.href);
                                    webincrementUrl.searchParams.set('winc_webincrementIdCode',webincrementInstallationCode);
                                    window.location.href = webincrementUrl.href;
                                }else{
                                    alert('The installation code is wrong.');
                                }
                            }else if (this.readyState == 4){
                                alert('The installation code is wrong.'); 
                            }
                            document.getElementById("webincrementSpinnerDiv").style.display = "none";
                        };
                        webincrementXhttp.send(null);
                    }
					
                    function winc_webincrementValidateRegisterNameField(){
                        document.getElementById("webincrement_register_error_name").style.display="none";
                        if(!document.getElementById("webincrement_register_name").value){
                            winc_webincrementOpenRegisterErrorName();
                            return false;
                        }
                        return true;
                    }
                    function winc_webincrementOpenRegisterErrorName(){
                        document.getElementById("webincrement_register_name").focus();
                        document.getElementById("webincrement_register_error_name").style.display="block";
                        document.getElementById("webincrementSpinnerDiv").style.display = "none";
                    }
                    function winc_webincrementCloseRegisterErrorName(){
                        document.getElementById("webincrement_register_error_name").style.display="none";
                    }
					
                    function winc_webincrementValidateRegisterEmailField(){
                        document.getElementById("webincrement_register_error_email").style.display="none";
                        if(!document.getElementById("webincrement_register_email").value || !validateEmail(document.getElementById("webincrement_register_email").value)){
                            winc_webincrementOpenRegisterErrorEmail();
                            return false;
                        }
                        return true;
                    }
                    function winc_webincrementOpenRegisterErrorEmail(){
                        document.getElementById("webincrement_register_email").focus();
                        document.getElementById("webincrement_register_error_email").style.display="block";
                        document.getElementById("webincrementSpinnerDiv").style.display = "none";
                    }
                    function winc_webincrementCloseRegisterErrorEmail(){
                        document.getElementById("webincrement_register_error_email").style.display="none";
                    }

					function winc_webincrementValidateRegisterPasswordField(){
                        document.getElementById("webincrement_register_error_password").style.display="none";
                        if(!document.getElementById("webincrement_register_password").value){
                            winc_webincrementOpenRegisterErrorPassword();
                            return false;
                        }
                        return true;
                    }
                    function winc_webincrementOpenRegisterErrorPassword(){
                        document.getElementById("webincrement_register_password").focus();
                        document.getElementById("webincrement_register_error_password").style.display="block";
                        document.getElementById("webincrementSpinnerDiv").style.display = "none";
                    }
                    function winc_webincrementCloseRegisterErrorPassword(){
                        document.getElementById("webincrement_register_error_password").style.display="none";
                    }
					
					function winc_webincrementValidateRegisterPassword2Field(){
                        document.getElementById("webincrement_register_error_password2").style.display="none";
                        if(!document.getElementById("webincrement_register_password2").value){
                            winc_webincrementOpenRegisterErrorPassword2();
                            return false;
                        }
                        return true;
                    }
                    function winc_webincrementOpenRegisterErrorPassword2(){
                        document.getElementById("webincrement_register_password2").focus();
                        document.getElementById("webincrement_register_error_password2").style.display="block";
                        document.getElementById("webincrementSpinnerDiv").style.display = "none";
                    }
                    function winc_webincrementCloseRegisterErrorPassword2(){
                        document.getElementById("webincrement_register_error_password2").style.display="none";
                    }
					
					function winc_webincrementValidateRegisterAgreeField(){
                        document.getElementById("webincrement_register_error_agree").style.display="none";
                        if(!document.getElementById("webincrement_register_agree").checked){
                            winc_webincrementOpenRegisterErrorAgree();
                            return false;
                        }
                        return true;
                    }
                    function winc_webincrementOpenRegisterErrorAgree(){
                        document.getElementById("webincrement_register_agree").focus();
                        document.getElementById("webincrement_register_error_agree").style.display="block";
                        document.getElementById("webincrementSpinnerDiv").style.display = "none";
                    }
                    function winc_webincrementCloseRegisterErrorAgree(){
                        document.getElementById("webincrement_register_error_agree").style.display="none";
                    }					
					
					//Login
                    function winc_webincrementValidateLoginEmailField(){
                        document.getElementById("webincrement_login_error_email").style.display="none";
                        if(!document.getElementById("webincrement_login_email").value || !validateEmail(document.getElementById("webincrement_login_email").value)){
                            winc_webincrementOpenLoginErrorEmail(); 
                            return false;
                        }
                        return true;
                    }
                    function winc_webincrementOpenLoginErrorEmail(){
                        document.getElementById("webincrement_login_email").focus();
                        document.getElementById("webincrement_login_error_email").style.display="block";
                        document.getElementById("webincrementSpinnerDiv").style.display = "none";
                    }
                    function winc_webincrementCloseLoginErrorEmail(){
                        document.getElementById("webincrement_login_error_email").style.display="none";
                    }

					function winc_webincrementValidateLoginPasswordField(){
                        document.getElementById("webincrement_login_error_password").style.display="none";
                        if(!document.getElementById("webincrement_login_password").value){
                            winc_webincrementOpenLoginErrorPassword();
                            return false;
                        }
                        return true;
                    }
                    function winc_webincrementOpenLoginErrorPassword(){
                        document.getElementById("webincrement_login_password").focus();
                        document.getElementById("webincrement_login_error_password").style.display="block";
                        document.getElementById("webincrementSpinnerDiv").style.display = "none";
                    }
                    function winc_webincrementCloseLoginErrorPassword(){
                        document.getElementById("webincrement_login_error_password").style.display="none";
                    }					
					
					const validateEmail = (email) => {
						return email.match(
							/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
						);
					};
					
					/*document.addEventListener('readystatechange', event => { 
						var webincrement_installation_code_input = document.getElementById("webincrement_installation_code");
						webincrement_installation_code_input.addEventListener("keypress", function(event) {
						  if (event.key === "Enter") {
							event.preventDefault();
							document.getElementById("webincrement_installation_code_submit_button").click();
						  }
						});
					});*/			
                </script>
            <?php
        }
        ?>
            <style>
                #webincrementSpinnerDiv{display:none;}
                .loader {
                    border: 4px solid #f3f3f3; /* Light grey */
                    border-top: 4px solid #1e88e5; /* Blue */
                    border-radius: 50%;
                    width: 120px;
                    height: 120px;
                    animation: spin 1.2s linear infinite;
                }
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                .leftContainer{
                    width:calc(60% - 30px); margin-left: 20px; margin-top: 20px; float:left;
                }
                .rightContainer{
                    width:calc(40% - 60px); padding-left: 30px; float:left;
                }
                .leftContainerLogin{
                    width:60%; float:left;
                }
                .rightContainerLogin{
                    width:calc(40% - 20px); float:left;
                }
                .classGoToInstallationCode{
                    font-size: 14px; font-weight: bold; float: right; margin-top: 25px;
                }
                .openWebIncrementControlPanelButtom{
                    text-decoration: none; background: #1e88e5; position: absolute; margin-left: -132px; padding: 10px 22px 12px 22px; border-radius: 4px; border: 1px solid #1e88e5; color: white;
                }

                @media (max-width: 767px) {
                    .leftContainer{
                        width:100%;
                        margin-left: 6px;
                    }
                    .rightContainer{
                        width:calc(100% - 60px); margin-top: 20px;
                    }
                    .leftContainerLogin{
                        width:100%; padding-left: 5px !important;
                    }
                    .rightContainerLogin{
                        width:100%; margin-top: 40px;
                    }
                    .headerBar{
                        text-align: left !important;
                        padding-right: 20px;
                    }
                    .headerBar img{
                        height: 30px !important; padding-top: 24px !important; margin-left: 200px; width: calc(100% - 200px);
                    }
                    .classGoToInstallationCode{
                        font-size: 12px; font-weight: bold; float: none; margin-top: 10px;
                    }
                    .openWebIncrementControlPanelButtom{
                        width: 150px; margin-left: -98px;
                    }
                    .openWebIncrementControlPanelButtomContainer{
                        padding-bottom: 100px !important;
                    }
                }
                @media (max-width: 460px) {
                    .headerImagesContainerLogin .icon{
                        height:92px !important;
                    }
                    .headerImagesContainerLogin .name{
                        height:33px !important; margin-left: -20px !important;
                    }
                }
                @media (max-width: 380px) {
                    .headerImagesContainer img{
                        height: 20px !important;
                        width: auto !important;
                    }
                    .headerImagesContainerLogin .name{
                        margin-left: 0px !important;
                    }
                }
            </style>
        <?php
    }

?>