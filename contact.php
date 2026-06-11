<!DOCTYPE html>
<html> 
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="generator" content="RocketCake">
                <link rel="icon" href="favicon.ico" type="image/x-icon">
	<title>Contact the Jackelope of Truth</title>
	<link rel="stylesheet" type="text/css" href="contact_php.css?h=da8a3e42">
</head>
<body>
<div class="textstyle1">
<div id="container_2eed27d"><div id="container_2eed27d_padding" ><div class="textstyle2"><div id="container_318d4fa8"><div id="container_318d4fa8_padding" ><div class="textstyle2">  <span class="textstyle3">Contact the Jackelope of Truth</span>
</div>
</div></div><div id="menu_affa515"><div  class="menuholder1"><a href="javascript:void(0);" tabindex="-1" aria-label="Menu &#9776;" >
	<div id="menuentry_4399966c"  class="menustyle1 menu_affa515_mainMenuEntry mobileEntry">
		<div class="menuentry_text1">
  <span class="textstyle4">Menu &#9776;</span>
		</div>
	</div>
</a>
<a href="index.html" style="text-decoration:none" tabindex="-1">
	<div id="menuentry_32be629e"  class="menustyle1 menu_affa515_mainMenuEntry normalEntry">
		<div class="menuentry_text2">
  <span class="textstyle5">Home</span>
		</div>
	</div>
</a>
<a href="about/index.html" style="text-decoration:none" tabindex="-1">
	<div id="menuentry_9bce134"  class="menustyle2 menu_affa515_mainMenuEntry normalEntry">
		<div class="menuentry_text2">
  <span class="textstyle5">About</span>
		</div>
	</div>
</a>
<a href="javascript:void(0);" tabindex="-1" aria-label="Contact" >
	<div id="menuentry_754b9d33"  class="menustyle3 menu_affa515_mainMenuEntry normalEntry">
		<div class="menuentry_text2">
  <span class="textstyle5">Contact</span>
		</div>
	</div>
</a>
<a href="" style="text-decoration:none" tabindex="-1">
	<div id="menuentry_ca04df8"  class="menustyle4 menu_affa515_mainMenuEntry normalEntry">
		<div class="menuentry_text2">
  <span class="textstyle5">Fannish Lore</span>
		</div>
	</div>
</a>

	<script type="text/javascript" src="rc_images/wsp_menu.js"></script>
	<script type="text/javascript">
		var js_menu_affa515= new wsp_menu('menu_affa515', 'menu_affa515', 10, null, true, {generateAriaLabels: true, setUsefulTabIndices: true, closeWhenMouseOut: false} );

		js_menu_affa515.createMenuForItem('menuentry_4399966c', ["  <span class=\"textstyle6\">Home</span> ", 'index.html', '',
		                                   "  <span class=\"textstyle6\">About</span> ", 'about/index.html', '',
		                                   "  <span class=\"textstyle6\">&#160;&#160;&#160;Who I Am and How I Came To Be</span> ", 'about/whoami.html', '',
		                                   "  <span class=\"textstyle6\">&#160;&#160;&#160;How Gen-X Changed the World</span> ", 'about/gen-x.html', '',
		                                   "  <span class=\"textstyle6\">Contact</span> ", 'javascript:void(0);', '',
		                                   "  <span class=\"textstyle6\">Fannish Lore</span> ", '', '',
		                                   "  <span class=\"textstyle6\">&#160;&#160;&#160;The Ballad of James Dixon</span> ", '', '']
		                                   , true);
		js_menu_affa515.createMenuForItem('menuentry_32be629e', []);
		js_menu_affa515.createMenuForItem('menuentry_9bce134', ["  <span class=\"textstyle5\">Who I Am and How I Came To Be</span> ", 'about/whoami.html', '',
		                                   "  <span class=\"textstyle5\">How Gen-X Changed the World</span> ", 'about/gen-x.html', '']);
		js_menu_affa515.createMenuForItem('menuentry_754b9d33', []);
		js_menu_affa515.createMenuForItem('menuentry_ca04df8', ["  <span class=\"textstyle5\">The Ballad of James Dixon</span> ", '', '']);

	</script>
</div></div><div id="container_459f1faa"><div id="container_459f1faa_padding" ><div class="textstyle2"><div id="elem_67f07d53"  style="vertical-align: top; position:relative; display: inline-block; width:50%; height:350px; min-width:350px; background-color:#E5E5E5; " ><form action="contact.php" enctype="application/x-www-form-urlencoded" method="POST">  <div id="text_7f0e379e">
    <div class="textstyle1">
<span class="textstyle7"><br/>Contact Form<br/><br/></span><div id="container_3ef7e28a"><div id="container_3ef7e28a_padding" ><div class="textstyle2"><span class="textstyle7"><br/></span><label id="label_9faa198" for="edit_dda4e7e">Name:</label><input type="text" value="" title="" name="NameField" required="required"  id="edit_dda4e7e" >
<span class="textstyle7"><br/><br/></span><label id="label_23baf3ea" for="edit_12077a6">Email:</label><input type="text" value="" title="" name="EmailField" required="required"  id="edit_12077a6" >
<span class="textstyle7"><br/><br/></span><input type="text" value="" title="" name="antiSpamAnswer" required="required"  id="edit_64589801" >
<label id="label_18bafa13" for="edit_64589801">The Answer to the Ultimate Question</label><span class="textstyle7"><br/><br/></span><textarea name="TextField" title="" required="required" cols="20" rows="4"  id="edit_33ab7473"></textarea>
<span class="textstyle7"><br/><br/></span><label id="label_23e9de1e" for="edit_33ab7473">Your Message</label></div>
<div style="clear:both"></div></div></div><input name="Button1" type="submit" value="Send" title=""  id="button_28553e3e" >
<span class="textstyle7"><br/><br/></span>      </div>
    </div>
</form>
<?PHP
if (count($_POST)>1)
{ 
  if (isset($_POST['antiSpamAnswer']) && strcasecmp($_POST['antiSpamAnswer'],'18')==0)
  {
    $text = "";
    foreach($_POST as $name => $value)
    {
       $text .= "$name : $value\n";
    }
    if ($text != "")
    {
      echo 'Thanks for contacting us';
      echo '<script type="text/javascript">var e = document.getElementById("elem_67f07d53"); e.firstChild.style.display = "none";</script>';
      $headers = "Content-Type: text/plain; charset=UTF-8";
      mail("email@example.com", "Contact form request", $text, $headers);
    }
  }
  else
  { 
    echo "<p style='color:red'>Sorry, you need to answer the anti-Spam message correctly.</p>";
    foreach($_POST as $name => $value)
    { 
       echo '<script type="text/javascript">var e = document.getElementsByName("' . $name . '")[0]; e.value = ' . json_encode($value) . ';</script>';    }
  }
}
?>
</div></div>
<div style="clear:both"></div></div></div><span class="textstyle8"><br/></span></div>
<div style="clear:both"></div></div></div>  </div>
</body>
</html>