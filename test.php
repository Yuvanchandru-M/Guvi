<?php
	try{
		$conn = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	} catch (MongoDBDriverExceptionException $e) {
		echo 'Failed to connect to MongoDB, is the service intalled and running?<br /><br />';
		echo $e->getMessage();
		exit();
	}

	$t_array = array(
		array('id'=>'4', 'age'=> '20','contact'=>'9876543210'),
		array('id'=>'5', 'age'=> '19','contact'=>'9850543210')
	);

	$cmd = new MongoDB\Driver\Command(['listDatabases' => 1]);
	try {
		$result = $conn->executeCommand('admin', $cmd);
		$dbArray = $result->toArray()[0];
	} catch(MongoDB\Driver\Exception $e) {
		echo $e->getMessage().'<br />';
		exit;
	}

	if(!array_search('guvi', array_column($dbArray->databases, 'name'))){
		echo 'guvi database doesn\'t exist, creating it<br />';
		foreach($t_array AS $user_profile){
			$row = new MongoDB\Driver\BulkWrite();
			$row->insert($user_profile);
			$conn->executeBulkWrite('guvi.user_profile', $row);
			echo '&emsp;Added '.$user_profile['id'].'<br />';
		}
	} 

	$query = new MongoDB\Driver\Query([],[]);
	$result = $conn->executeQuery('guvi.user_profile', $query);

	if($result){
		echo '<h3>Reading data from MongoDB</h3>'.
		'<table width="500" align="center">'.
			'<thead>'.
				'<tr><th>_id</th><th>id</th><th>contact</th></tr>'.
			'</thead>'.
			'<tbody>';
				foreach ($result as $rs){
					echo '<tr><td>'.$rs->{'_id'}.'</td><td>'.$rs->id.'</td><td>'.$rs->contact.'</td></tr>';
				}
			echo '</tbody>'.
		'</table>';
		unset($query, $result);
		
		$query = new MongoDB\Driver\Query(['id'=> '1'],[]);
		$result = $conn->executeQuery('guvi.user_profile', $query);
		if($result){
			$rs = $result->toArray()[1];
			echo '<h3>Reading specific user_profile from MongoDB</h3>'.
			'The capital of '.$rs->id.' is '.$rs->contact.'<br /><br />_id: '.$rs->{'_id'};
		}
	}
?>