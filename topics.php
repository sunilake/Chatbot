<?php
	require_once("inc/functions.inc.php");
	require_once("inc/variables.inc.php");

	// $username_arr = $_POST["username"];
	$username_arr = array("smart-sachin");
	$repos = array();

	foreach ($username_arr as $username) {
		$data = curl("https://api.github.com/users/$username/repos");
		$data = json_decode($data, true);
		foreach ($data as $repo) {
			$repo_url = $repo["html_url"];
			$repo_content = file_get_contents($repo_url);
			$regex = '#<\s*?article\b[^>]*>(.*?)</article\b[^>]*>#s';
			preg_match_all($regex, $repo_content, $readme);
			// echo($readme[0][0]);
			foreach ($topics as &$topic) {
				if (@stripos($readme[0][0], $topic["name"]) !== false) {
					$topic["freq"] += 1;
				}
			}
		}
	}
	function cmp($a, $b) {
		return $b["freq"] - $a["freq"];
	}

	usort($topics, "cmp");
	print_r($topics);
?>