<html>
<head>
<?php 
  //This file should contain your app secret and app id.
  require_once('fb_info.php');
?>
<style>
a.game_action { color:#f00; border:1px solid #000; padding:1px; }
#log { clear:both; }


</style>

  <link rel="stylesheet" type="text/css" href="tabs.css" />
  <link rel="stylesheet" type="text/css" href="http://localhost/codeconsole/css/prettify.css" />
  <script type="text/javascript" src="http://localhost/codeconsole/js/prettify.js"></script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
  <script type="text/javascript" src="ajaxp.js"></script>
  <script src="http://connect.facebook.net/en_US/all.js"></script>
  <script>

    var xhr_address = "xhr_jsonp.php";

    var script_param_count = 0;

   // Set up the functions to call when some buttons are pressed.
   // We use jquery and the ajax functions bound to the buttons.

   $(document).ready( function() { //START STUFF TO DO AFTER LOAD

        $('#fb_oauth_create_token').val( fb_oauth_token );
        $('#fb_oauth_login_token').val( fb_oauth_token );
        $('#fb_signed_request_create_token').val( fb_signed_request );
        $('#fb_signed_request_login_token').val( fb_signed_request );
        $('#fb_signed_request_bind_token').val( fb_signed_request );

	$('#create_div a').click( function(){ 
		var data = { 
			name:$('#create_username').val(),
			hash:$('#create_hash').val()
		};
		$('#login_username').val( $('#create_username').val() );
		$('#login_hash').val( $('#create_hash').val() );
		var on_create = function(xml) {
			//Should have some error handling in here...
			$('#create_div').hide();
			$('#login_div').show();
			show_xml(xml);
		};
		do_ajax( on_create, 'user/create/', data )
	} );

	$('#ping_div a').click( function(){ 
		do_ajax( show_xml, 'info/ping/', {} );
	} );

	$('#login_div a').click( function(){ 
		var data = { 
			name:$('#login_username').val(),
			hash:$('#login_hash').val()
		};
		var on_create = function(xml) {
			$('#login_div').hide();
			$('#game_div').show();
			$('#auth_token').val( $(xml).find("auth_token").text() );
			show_xml(xml);
		};
		do_ajax( on_create, 'user/login/', data );
	} );

	$('#fb_oauth_create_div a').click( function() {
		do_ajax(
			function(xml) { 
			$('#fb_oauth_create_div').hide();
			$('#fb_oauth_login_div').show();
			$('#fb_oauth_login_token').val( $('#fb_oauth_create_token').val() );
			show_xml(xml);
			},
			'facebook/create_oauth/',
			{
				name:$('#fb_oauth_create_username').val(),
				oauth_token:$('#fb_oauth_create_token').val()
			}
		);
	} );

	$('#fb_oauth_login_div a').click( function() {
		do_ajax(
			function(xml) { 
			$('#fb_oauth_login_div').hide();
			$('#game_div').show();
			$('#auth_token').val( $(xml).find("auth_token").text() );
			show_xml(xml);
			},
			'facebook/login_oauth/',
			{
				oauth_token:$('#fb_oauth_login_token').val()
			}
		);
	} );

	$('#fb_signed_create_div a').click( function() {
		do_ajax(
			function(xml) { 
			$('#fb_signed_create_div').hide();
			$('#fb_signed_login_div').show();
			$('#fb_signed_request_login_token').val( $('#fb_signed_request_create_token').val() );
			show_xml(xml);
			},
			'facebook/create_signed/',
			{
				name:$('#fb_signed_create_username').val(),
				signed_request:$('#fb_signed_request_create_token').val()
			}
		);
	} );

	$('#fb_signed_login_div a').click( function() {
		do_ajax(
			function(xml) { 
			$('#fb_signed_login_div').hide();
			$('#game_div').show();
			$('#auth_token').val( $(xml).find("auth_token").text() );
			show_xml(xml);
			},
			'facebook/login_signed/',
			{
				signed_request:$('#fb_signed_request_login_token').val()
			}
		);
	} );

	$('#game_info').click( function(){ 
		var data = { 
			auth_token:$('#auth_token').val()
		};
		do_ajax( show_xml, 'user/view/', data );
	} );

	$('#social_invite').click( function(){ 
		var data = { 
			auth_token:$('#auth_token').val(),
			friend_id:0
		};
		do_ajax( show_xml, 'social/invite_send/', data );
	} );

	$('#social_invite_info').click( function(){ 
		var data = { 
			auth_token:$('#auth_token').val(),
			invite_id:$('#invite_id').val()
		};
		do_ajax( show_xml, 'social/invite_info/', data );
	} );

	$('#social_invite_accept').click( function(){ 
		var data = { 
			auth_token:$('#auth_token').val(),
			invite_id:$('#invite_id').val()
		};
		do_ajax( show_xml, 'social/invite_accept/', data );
	} );

	$('#social_friend_show').click( function(){ 
		var data = { 
			auth_token:$('#auth_token').val(),
		};
		do_ajax( show_xml, 'social/friend_show/', data );
	} );

	$('#shop_show').click( function(){ 
		var data = { 
			auth_token:$('#auth_token').val(),
		};
		do_ajax( show_xml, 'shop/list/', data );
	} );

	$('#shop_buy').click( function(){ 
		var data = { 
			shop_item_ikey:$('#shop_buy_ikey').val(),
			auth_token:$('#auth_token').val(),
		};
		do_ajax( show_xml, 'shop/buy/', data );
	} );

	$('#inventory').click( function(){ do_ajax( show_xml, 'items/list/', { auth_token:$('#auth_token').val() } ); } );

	$('#what_can_i_send').click( function(){ do_ajax( show_xml, 'mail/what_can_i_send/', { auth_token:$('#auth_token').val() } ); } );

	$('#what_can_i_accept').click( function(){ do_ajax( show_xml, 'mail/what_can_i_accept/', { auth_token:$('#auth_token').val() } ); } );

	$('#send').click( function(){ do_ajax( show_xml, 'mail/send/', {
		auth_token:$('#auth_token').val(),
		recipient_id:$('#send_recipient_id').val(),
		mailable_id:$('#send_mailable_id').val(),
		message:$('#send_message').val()
		} ); } );

	$('#accept').click( function(){ do_ajax( show_xml, 'mail/accept/', {
		auth_token:$('#auth_token').val(),
		mail_id:$('#accept_mail_id').val()
		} ); } );

	$('#fb_friends').click( function(){ do_ajax( show_xml, 'facebook/friends/', {
		auth_token:$('#auth_token').val()
		} ); } );

	$('#fb_bind').click( function(){ do_ajax( show_xml, 'facebook/bind_signed/', {
		auth_token:$('#auth_token').val(),
		signed_request:$('#fb_signed_request_bind_token').val()
		} ); } );


	$('#script_run').click( function(){
		var params =  {
			auth_token:$('#auth_token').val(),
			script:$('#scriptname').val(),
		};
		var args = {};
		for( var i=0; i < script_param_count; i++ )
		{
			args[$('#script_param_key'+i).val()] = $('#script_param_value'+i).val();
		}
		console.log(args);
		console.log( JSON.stringify(args) );
                params['args']=JSON.stringify(args); 
		do_ajax( show_xml, 'scripts/run/', params);
		} );

	$('#script_add_param').click( function(){
		$('#script_params').append('<input id="script_param_key'+script_param_count+'"/><input id="script_param_value'+script_param_count+'"/><br/>');
		script_param_count += 1;
		} );

	$('#fb_shop_list').click( function(){ 
		var data = { 
			auth_token:$('#auth_token').val(),
		};
		do_ajax( show_xml, 'facebook/shop_list/', data );
	} );

	$('#fb_buy').click( function(){
		var order_info = {
			item:$('#fb_item_ikey').val(),
			roar_id:$('#fb_buy_player_id').val()
			};
		var fb_call_obj = {
			method:'pay',
			order_info: order_info,
			purchase_type: 'item'
			};
		FB.ui(fb_call_obj, function(data) { console.log(data); } );
	} );

	//Trial pay code
	$('#fb_trialpay').click( function(){
		var obj = {
			method: 'pay',
			action: 'earn_currency',
			product: '<?php echo $root_url;?>currency_info.php'
		};

		FB.ui(obj, function(data) { console.log(data); } );
	});
	$('#fb_earn').click( function(){
		var obj = {
			method: 'pay',
			action: 'earn_credits'
		};

		FB.ui(obj, function(data) { console.log(data); } );
	});

    //the callback should (aparently) look more like this.
//    var callback = function(data) {
//      if (data['order_id']) {
//        return true;
//      } else {
//        //handle errors here
//        return false;
//      }
//    };

	//TABS CODE
	//When page loads...
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content

	//On Click Event
	$("ul.tabs li").click(function() {

		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content

		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});

   } ); //END STUFF TO DO AFTER LOAD


    ///
    /// Display functions
    ///
    var show_xml = function(xml)
    {
	$('#codeblock').text(xml);
	prettyPrint();
	console.log(xml);
    };

  </script>
</head>
<body>


<!-- ==================================================== -->
<!-- == Boiler plate code for facebook credits testing == -->
<!-- ==================================================== -->
<div id="fb-root"></div>
<script>var app_id = <?php print $app_id; ?>;</script>

<script>
    FB.init({appId:app_id, status:true, cookie:true});

    function writeback(str) {
      document.getElementById('output').innerHTML=str;
    }
</script>
<!-- =========================================================== -->
<!-- == End of Boiler plate code for facebook credits testing == -->
<!-- =========================================================== -->

<!-- ==================================================== -->
<!-- == Code for decoding facebook signed requests     == -->
<!-- ==================================================== -->
<?php 


  function parse_signed_request($signed_request, $secret) {
  list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

  // decode the data
  $sig = base64_url_decode($encoded_sig);
  $data = json_decode(base64_url_decode($payload), true);

  if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
    error_log('Unknown algorithm. Expected HMAC-SHA256');
    return null;
  }

  // check sig
  $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
  if ($sig !== $expected_sig) {
    error_log('Bad Signed JSON signature!');
    return null;
  }

  return $data;
}

function base64_url_decode($input) {
  return base64_decode(strtr($input, '-_', '+/'));
}
  $req_data =  parse_signed_request( $_POST['signed_request'], $secret );

?>
<script>
  var fb_oauth_token = "<?php print($req_data['oauth_token']); ?>";
  var fb_signed_request = "<?php print($_POST['signed_request']); ?>";
  var canvas_page = "<?php print($canvas_page); ?>";
</script>
<!-- ======================================================== -->
<!-- == End Code for decoding facebook signed requests     == -->
<!-- ======================================================== -->

<?php
  if( ! isset( $req_data['user_id'] ) )
  {
?>
  <h2>You have not authorised this app ...</h2>
  <p> Click the 
<a class="game_action" onclick='top.location.href="http://www.facebook.com/dialog/oauth?client_id="+app_id+"&redirect_uri="+encodeURIComponent(canvas_page); return false'>AUTH</a><BR>
link to authorise</p>
<?php } ?>

<div id="ping_div">
<a class="game_action">PING!</a>
</div><BR><BR><BR>

<div id="chooser">
<a class="game_action" onclick="$('#create_div').show(); $('#chooser').hide();" >CREATE</a><br> 
<a class="game_action" onclick="$('#login_div').show(); $('#chooser').hide();" >LOG IN</a><br>
<a class="game_action" onclick="$('#fb_oauth_create_div').show(); $('#chooser').hide();" >FB OAUTH CREATE</a><br>
<a class="game_action" onclick="$('#fb_oauth_login_div').show(); $('#chooser').hide();" >FB OAUTH LOGIN</a><br>
<a class="game_action" onclick="$('#fb_signed_create_div').show(); $('#chooser').hide();" >FB SIGNED REQUEST CREATE</a><br>
<a class="game_action" onclick="$('#fb_signed_login_div').show(); $('#chooser').hide();" >FB SIGNED REQUEST LOGIN</a><br>
</div>

<div id="create_div" style="display:none;">
<h2>CREATE</h2>
<table>
<tr><td>Username</td><td><input id="create_username"/></td></tr>
<tr><td>Password</td><td><input id="create_hash"/></td></tr>
</table>
<a class="game_action">create</a>
</div>

<div id="login_div" style="display:none;">
<h2>LOG IN</h2>
<table>
<tr><td>Username</td><td><input id="login_username"/></td></tr>
<tr><td>Password</td><td><input id="login_hash"/></td></tr>
</table>
<a class="game_action">login</a>
</div>

<div id="fb_oauth_create_div" style="display:none;">
<h2>CREATE</h2>
<table>
<tr><td>Username</td><td><input id="fb_oauth_create_username"/></td></tr>
<tr><td>oauth token</td><td><input id="fb_oauth_create_token"/></td></tr>
</table>
<a class="game_action">create</a>
</div>

<div id="fb_oauth_login_div" style="display:none;">
<h2>LOG IN</h2>
<table>
<tr><td>oauth_token</td><td><input id="fb_oauth_login_token"/></td></tr>
</table>
<a class="game_action">login</a>
</div>

<div id="fb_signed_create_div" style="display:none;">
<h2>CREATE</h2>
<table>
<tr><td>Username</td><td><input id="fb_signed_create_username"/></td></tr>
<tr><td>signed request</td><td><input id="fb_signed_request_create_token"/></td></tr>
</table>
<a class="game_action">create</a>
</div>

<div id="fb_signed_login_div" style="display:none;">
<h2>LOG IN</h2>
<table>
<tr><td>signed request</td><td><input id="fb_signed_request_login_token"/></td></tr>
</table>
<a class="game_action">login</a>
</div>

<div id="game_div" style="display:none;">
AUTH TOKEN:<input id="auth_token"/><BR>
<ul class="tabs">
    <li><a href="#general_tab">General</a></li>
    <li><a href="#social_tab">Social</a></li>
    <li><a href="#items_tab">Items</a></li>
    <li><a href="#script_tab">Scripts</a></li>
    <li><a href="#soc_net_tab">Soc. Net.</a></li>
    <li><a href="#fb_shop">FB. Shop.</a></li>
</ul>

<div class="tab_container">
  <div id="general_tab" class="tab_content">
    <a id="game_info" class="game_action">info</a>
  </div>
  <div id="social_tab" class="tab_content">
    <a id="social_invite" class="game_action">generate invite</a><br>
    <input id="invite_id"> <a id="social_invite_info" class="game_action">invite info</a> <a id="social_invite_accept" class="game_action">accept invite</a><br>
    <a id="social_friend_show" class="game_action">friends</a><BR>
    <a id="what_can_i_send" class="game_action">what can i send?</a><BR>
    <a id="what_can_i_accept" class="game_action">what can i accept?</a><BR>
    <a id="send" class="game_action">send</a>friend id:<input id="send_recipient_id"> mailable id:<input id="send_mailable_id"> message<input id="send_message"><BR>
    <a id="accept" class="game_action">accept</a>mail id<input id="accept_mail_id"><BR>
  </div>
  <div id="items_tab" class="tab_content">
    <a id="inventory" class="game_action">inventory</a><br>
    <a id="shop_show" class="game_action">list shop</a><br>
    <a id="shop_buy" class="game_action">buy from shop</a><input id="shop_buy_ikey"><br>
  </div>
  <div id="script_tab" class="tab_content">
     script name: <input id="scriptname"><br>
     <a id="script_run" class="game_action">run</a><br>
     <div id="script_params"></div>
     <a id="script_add_param" class="game_action">+</a>
  </div>
  <div id="soc_net_tab" class="tab_content">
     <a id="fb_friends" class="game_action">facebook friends</a><br>
     <a id="fb_bind" class="game_action">facebook bind</a> Signed Request:<input id="fb_signed_request_bind_token"><br>
     <a id="fb_login" class="game_action" onclick="$('#fb_oauth_login_div').show();">login again</a>
  </div>
  <div id="fb_shop" class="tab_content">
     <a id="fb_shop_list" class="game_action">List Shop Items</a><BR>
     <a id="fb_buy" class="game_action">Buy</a> item ikey:<input id="fb_item_ikey"> player id:<input id="fb_buy_player_id">
     <div>
       <a id="fb_trialpay" class="game_action">Complete offers for in-game cash</a><BR>
       <a id="fb_earn" class="game_action">Complete offers for facebook credits</a><BR>
     </div>
  </div>
</div>

</div>



<div id="log">
<pre class="prettyprint lang-xml" id="codeblock"></pre>
</div>

</body>
</html>
