<?php 
	require_once __DIR__ . '/php-graph-sdk-5.0.0/src/Facebook/autoload.php';
	$fb = new Facebook\Facebook([
  		'app_id' => '167633913744385',
  		'app_secret' => 'c68eb9b50f9ab48262fe1c5b8dba2e01',
  		'default_graph_version' => 'v2.8',
  		'default_access_token' => 'EAACYdkZChjAEBAGDBIZAGBcrAdHgZABrZBLjSdtQp3pWccsNZAYZAl9JoWvVjU7iYLc0AaZBOZAB5MZCP229ogIjM1k0cY7w1eNLjwHbqyTtDOddgeC5AYGanQZAmXkIiCjGIXPAL1Tm1rZAIbDzanZBvIXK6aUX918X3cEZD',
	]);
	$arrayIndex = array('user','page','event','group');
	//$results = array();
	if (!empty($_GET)) {

		if (!empty($_GET["id"])) { // to get details
			$request = $fb->request('GET', ''.$_GET['id'].'?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name,picture}},posts.limit(5)');
			$response = $fb->getClient()->sendRequest($request);
			echo json_encode($response->getDecodedBody());
		} else if (isset($_GET["offset"])) { // to get pagination data
			if ($_GET['type'] != 'place') {
				$request = $fb->request('GET', 'search?q='.$_GET['q'].'&type='.$_GET['type'].'&fields=id,name,picture.width(700).height(700)&offset='.$_GET['offset']);
			} else {
				$request = $fb->request('GET', 'search?q='.$_GET['q'].'&type='.$_GET['type'].'&fields=id,name,picture.width(700).height(700)&offset='.$_GET['offset'].'&center='.$_GET['center']);
			}
			$response = $fb->getClient()->sendRequest($request);
			echo json_encode($response->getDecodedBody());
			//echo "Hi there";
		} else if (isset($_GET['after'])) {
			$request = $fb->request('GET', 'search?q='.$_GET['q'].'&type='.$_GET['type'].'&fields=id,name,picture.width(700).height(700)&after='.$_GET['after']);
			$response = $fb->getClient()->sendRequest($request);
			echo json_encode($response->getDecodedBody());
		} else if (isset($_GET['before'])) {
			$request = $fb->request('GET', 'search?q='.$_GET['q'].'&type='.$_GET['type'].'&fields=id,name,picture.width(700).height(700)&before='.$_GET['before']);
			$response = $fb->getClient()->sendRequest($request);
			echo json_encode($response->getDecodedBody());
		}

		else { // normal search
			$index = $arrayIndex[0];
			foreach ($arrayIndex as $index) {
				$request = $fb->request('GET', 'search?q='.$_GET['keyword'].'&type='.$index.'&fields=id,name,picture.width(700).height(700)');
				$response = $fb->getClient()->sendRequest($request);
  				$results[$index] = $response->getDecodedBody();
  			
			}
			$request = $fb->request('GET', 'search?q='.$_GET['keyword'].'&type=place&fields=id,name,picture.width(700).height(700)&center='.$_GET['lat'].','.$_GET['long']);
			$response = $fb->getClient()->sendRequest($request);
  			$results['place'] = $response->getDecodedBody();
  			$results = json_encode($results);
  			echo $results;
		}
  	}
?>