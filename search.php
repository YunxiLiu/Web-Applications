<?php 
	require_once __DIR__ . '/php-graph-sdk-5.0.0/src/Facebook/autoload.php';
?>
<?php
	if (!empty($_GET) && !empty($_GET["id"]) && !empty($_GET["picture"])) :
?>
<?php 
	$fb = new Facebook\Facebook([
  		'app_id' => '167633913744385',
  		'app_secret' => 'c68eb9b50f9ab48262fe1c5b8dba2e01',
  		'default_graph_version' => 'v2.8',
  		'default_access_token' => 'EAACYdkZChjAEBAGDBIZAGBcrAdHgZABrZBLjSdtQp3pWccsNZAYZAl9JoWvVjU7iYLc0AaZBOZAB5MZCP229ogIjM1k0cY7w1eNLjwHbqyTtDOddgeC5AYGanQZAmXkIiCjGIXPAL1Tm1rZAIbDzanZBvIXK6aUX918X3cEZD',
	]);
	$request = $fb->request('GET', ''.$_GET['id'].'/picture?redirect=0');
	$response = $fb->getClient()->sendRequest($request);
  	$json = json_decode(json_encode($response->getDecodedBody()),true);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Picture</title>
	<meta http-equiv="refresh" content="0; url=<?php echo $json["data"]["url"]?>" />
</head>
<body>
</body>
</html>

<?php else :?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Facebook Search</title>
	<?php
	if (!empty($_GET)) {
		$fb = new Facebook\Facebook([
  			'app_id' => '167633913744385',
  			'app_secret' => 'c68eb9b50f9ab48262fe1c5b8dba2e01',
  			'default_graph_version' => 'v2.8',
  			'default_access_token' => 'EAACYdkZChjAEBAGDBIZAGBcrAdHgZABrZBLjSdtQp3pWccsNZAYZAl9JoWvVjU7iYLc0AaZBOZAB5MZCP229ogIjM1k0cY7w1eNLjwHbqyTtDOddgeC5AYGanQZAmXkIiCjGIXPAL1Tm1rZAIbDzanZBvIXK6aUX918X3cEZD',
		]);
		if (!empty($_GET["id"])) {
			if (empty($_GET["picture"])) {
				$request = $fb->request('GET', ''.$_GET['id'].'?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name,picture}},posts.limit(5)');
			}
		} else if ($_GET['type'] == 'event') {
			$request = $fb->request('GET', 'search?q='.$_GET['keyword'].'&type='.$_GET['type'].'&fields=id,name,picture.width(700).height(700),place');
		} else if ($_GET['type'] == 'place' && (!empty($_GET['location']) && !empty($_GET['distance']))) {
			$googleJson = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".$_GET['location']."&key=AIzaSyCOZ-7mS1HMdwkI32xCbCfbvZp8jnBYfFE"), true);
			$lat = $googleJson["results"][0]["geometry"]["location"]["lat"];
			$lng = $googleJson["results"][0]["geometry"]["location"]["lng"];
			$request = $fb->request('GET', 'search?q='.$_GET['keyword'].'&type='.$_GET['type'].'&center='.$lat.','.$lng.'&distance='.$_GET['distance'].'&fields=id,name,picture.width(700).height(700)');
		} else {
			$request = $fb->request('GET', 'search?q='.$_GET['keyword'].'&type='.$_GET['type'].'&fields=id,name,picture.width(700).height(700)');
		}
  		$response = $fb->getClient()->sendRequest($request);
  		$json = json_decode(json_encode($response->getDecodedBody()),true);
	}
	?>
<script type="text/javascript">
	function changeType(selectedObj) {
		if (selectedObj.options[selectedObj.selectedIndex].value=="place") {
			document.getElementById("extra").style.visibility="visible";
		} else {
			document.getElementById("extra").style.visibility="hidden";
		}
	}
	function resetform() {
		document.getElementsByName("type")[0].value="user";
		document.getElementsByName("keyword")[0].value="";
		document.getElementsByName("location")[0].value="";
		document.getElementsByName("distance")[0].value="";
		document.getElementById("extra").style.visibility="hidden";
		var album = document.getElementById("albums");
		var post = document.getElementById("posts");
		var albumNone = document.getElementById("noAlbum");
		var postNone = document.getElementById("noPost");
		var tabular = document.getElementById("tabular");
		if(tabular != null) {
			tabular.style.display="none";
		}
		if(album != null) {
			album.style.display="none";
		}
		if(post != null) {
			post.style.display="none";
		}
		if(noAlbum != null) {
			noAlbum.style.display="none";
		}
		if(noPost != null) {
			noPost.style.display="none";
		}
	}
	function showAlbum() {
		var album = document.getElementById("albumTable");
		if (album.style.display=="block") {
			album.style.display="none";
		} else {
			album.style.display="block";
		}
		document.getElementById("postTable").style.display="none";
	}
	function showPost() {
		var post = document.getElementById("postTable");
		if (post.style.display=="block") {
			post.style.display="none";
		} else {
			post.style.display="block";
		}
		document.getElementById("albumTable").style.display="none";
	}
	function showPhoto(e) {
		var photo = e.parentNode.parentNode.nextSibling.nextSibling;
		if (photo.style.display=="block") {
			photo.style.display="none";
		} else {
			photo.style.display="block";
		}
	}
</script>
</head>

<body>
<div style="margin-left:25%; padding-bottom: 13px; width:50%; border: 2px solid #d8d8d8;">
	<h2 align="center"><i>Facebook Search</i></h2>
	<p style="height:2px; margin-top:10px; margin-bottom:10px; margin-left:4px; margin-right:5px; background-color:#A0A0A0;"></p>
	<form id="myform" method="GET">
		<label for="keyword">Keyword</label>
		<input type="text" name="keyword" value="<?php echo isset($_GET["keyword"]) ? $_GET["keyword"] : "" ?>" required>
		<br />
		<label for="type" style="margin-right: 24px">Type:</label>
		<select name="type" onchange="changeType(this.form.type)">
  			<option value="user" <?php if(isset($_GET["type"]) && $_GET["type"] == 'user'){echo("selected");}?>>Users</option>
  			<option value="page" <?php if(isset($_GET["type"]) && $_GET["type"] == 'page'){echo("selected");}?>>Pages</option>
  			<option value="event" <?php if(isset($_GET["type"]) && $_GET["type"] == 'event'){echo("selected");}?>>Events</option>
  			<option value="place" <?php if(isset($_GET["type"]) && $_GET["type"] == 'place'){echo("selected");}?>>Places</option>
  			<option value="group" <?php if(isset($_GET["type"]) && $_GET["type"] == 'group'){echo("selected");}?>>Groups</option>
		</select>
		<br />

		<div id="extra" style="<?php if(isset($_GET["type"]) && $_GET["type"] == 'place'){echo("visibility: visible;");} else {echo("visibility: hidden;");}?>">
		<label for="location">Location</label>
		<input type="text" name="location" value="<?php echo isset($_GET["location"]) ? $_GET['location'] : '' ?>">
		<label for="distance">Distance(meters)</label>
		<input type="text" name="distance" value="<?php echo isset($_GET["distance"]) ? $_GET['distance'] : '' ?>">
		</div>
		<br />
		<input style="margin-left: 64px;" type="submit" name="submit" value="Search"/>
		<input type="button" name="button" onclick="resetform()" value="Clear">
		</form>
</div><br/>

<?php if (!empty($_GET)) ://if we get something?>
	<?php if (!empty($_GET["id"])) :?>
		<?php if (!empty($json["albums"]["data"])) :?>
			<div id="albums" style="margin-left:20%; text-align: left; margin-right: 20%;">
			<div align="center" style="background-color:#d8d8d8;  "><a href="javascript: showAlbum()">Albums</a></div><br/><br/>
			<table id="albumTable" border="2" style="display:none;">
			<col width="820">
  			<?php foreach ($json["albums"]["data"] as $item): ?>
  			<tr>
  				<td><?php if (!empty($item["photos"])){ ?><a href="#" onclick="showPhoto(this)">
  					<?php echo $item["name"]; ?>
  					</a>
  					<?php } else {echo $item["name"];}?>
  				</td>
  			</tr>
  			<?php if(!empty($item["photos"])) :?>
  			<tr style="display: none;">
  				<td>
  					<?php foreach ($item["photos"]["data"] as $pictureData): ?>
  						<a href="search.php?id=<?php echo $pictureData['id']; ?>&picture=true" target="_blank"><img src="<?php echo $pictureData['picture'];?>" height="80" width="80"></a>
  					<?php endforeach; ?>
  				</td>
  			</tr>
  			<?php endif; ?>
  			<?php endforeach; ?>
			</table>
			</div><br/>
		<?php else : ?>
			<div id="noAlbum" align="center" style="margin-left: 20%; margin-right: 20%;  border: 2px solid #d8d8d8; ">No Albums has been found</div><br/>
		<?php endif; ?>
		<?php if (!empty($json["posts"]["data"])) :?>
			<div id="posts" style="margin-left:20%; text-align: left; margin-right: 20%;">
			<div align="center" style="background-color:#d8d8d8;  "><a href="javascript: showPost()">Posts</a></div><br/>
			<table id="postTable" border="2" style="display: none;">
			<col width="820">
			<tr>
				<th>Message</th>
			</tr>
  			<?php foreach ($json["posts"]["data"] as $message): ?>
  				<?php if (!empty($message["message"])) :?>
  				<tr>
  					<td>
  						<?php echo $message["message"]; ?>
  					</td>
  				</tr>
  				<?php endif; ?>
  			<?php endforeach; ?>
			</table>
			</div><br/>
		<?php else : ?>
			<div id="noPost" align="center" style="margin-left: 20%; margin-right: 20%; border: 2px solid #d8d8d8;">No Posts has been found</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php if (!empty($_GET["submit"])) ://submit?>
		<?php if (!empty($json["data"])) :?>
		<div id="tabular" style="margin-left:20%; text-align: left; margin-right: 20%;">
		<table border="2">
		<col width="240">
  		<col width="400">
  		<col width="180">
  		<tr>
    		<th>Profile Photo</th>
    		<th>Name</th>
    		<th>
    		<?php if ($_GET["type"]=='event') {echo "Place";} else {echo "Details";}?>
    		</th>
  		</tr>
  		<?php foreach($json["data"] as $item): ?>
  		<tr>
  			<td>
  				<a href="<?php echo $item["picture"]["data"]["url"] ?>" target="_blank"><img src='<?php echo $item["picture"]["data"]["url"] ?>' height="30" width="40"></a>
  			</td>
  			<td>
  				<?php echo $item["name"]?>
  			</td>
  			<td>
  				<?php if ($_GET["type"]=='event') {if(!empty($item["place"])) {echo $item["place"]["name"];}} else {?>
  				<a href="search.php?id=<?php echo $item['id'];?>&keyword=<?php echo $_GET["keyword"];?>&type=<?php echo $_GET['type'];?>&location=<?php echo $_GET['location'];?>&distance=<?php echo $_GET['distance'];?>">Details</a>
  				<?php }?>
  			</td>
  		</tr>
  		<?php endforeach; ?>
		</table>
		</div>
		<?php else :?>
		<div align="center" style="margin-left:20%; margin-right: 20%; border: 2px solid #d8d8d8;">No Records has been found</div>
		<?php endif; ?>
	<?php endif; ?>
<?php endif;?>
</body>
</html>
<?php endif; ?>