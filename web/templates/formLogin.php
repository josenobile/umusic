<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Bienvenido a umusic.com!!</title>
<link href="css/main.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="js/jquery-ui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui/js/jquery-ui-1.8.5.custom.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/login.js"></script>
</head>
<body id="login_body">
<form id="login_form" action="" method="post" enctype="multipart/form-data">
<div id="capa_login">
    <div id="left_login">
    	<div id="logo_login">
    		<!--  <img src="images/logo_db.png" /> -->
        </div>        
        <table id="fields_form">
        	<?php if(isset($error_array) && ($numErrors = count($error_array) > 0)): ?>
            <tr>
        	  <td nowrap="nowrap"><?php echo "$numErrors error(s) found"; ?></td>
   	  	  	</tr>
            <?php endif; ?>
        	<tr>
            	<td nowrap="nowrap">Usuario : </td>
            </tr>
            <tr>
            	<td colspan="2" nowrap="nowrap"><input type="text" name="username" id="username" /> 
				<?php if(!empty($error_array["username"])) echo "<label class='error'>{$error_array["username"]}.</label>"; ?>                
                </td>
            </tr>    
    		<tr>
            	<td nowrap="nowrap">Contrase&ntilde;a : </td>
            </tr>
            <tr>
            	<td colspan="2" nowrap="nowrap"><input type="password" name="password" id="password" /> 
                <?php if(!empty($error_array["password"])) echo "<label class='error'>{$error_array["password"]}.</label>"; ?>
                </td>
            </tr>  
    		<tr>
            	<td nowrap="nowrap"><input  type="checkbox" name="remember" /> Recordarme.</td>
            </tr>
            <tr>
            	<td colspan="2"><input name="btnLogin" type="submit" id="btnLogin" value="Entrar"  /> <input name="btnCancel"  type="reset" id="btnCancel" value="Resetear"  /> </td>
            </tr>  
            <tr>
            	<td colspan="2"><a href="#" title="Forgot Your Password?">No puede entrar?</a></td>
            </tr> 
           </table>    
    </div>
    
    <div id="right_login">    
    	<div id="login_logo_lae">
        	<!-- <img src="images/logo_lae.gif"> -->
        </div>
    	<div id="text_logo_login">
        	<ul>
              <li>Gestionar el alquiler de autos.</li>             
             </ul>                   
        </div>
    </div>
</div>
</form>
</body>
</html>