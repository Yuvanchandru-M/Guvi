<?php
	try{
		$conn = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	} catch (MongoDBDriverExceptionException $e) {
		echo 'Failed to connect to MongoDB, is the service intalled and running?<br /><br />';
		echo $e->getMessage();
		exit();
	}

	$states = array(
		array('State'=>'Alabama', 'contact'=> 'Montgomery'),
		array('State'=>'Alaska', 'contact'=> 'Juneau'),
		array('State'=>'Arizona', 'contact'=> 'Phoenix'),
		array('State'=>'Arkansas', 'contact'=> 'Little Rock'),
		array('State'=>'California', 'contact'=> 'Sacramento'),
		array('State'=>'Colorado', 'contact'=> 'Denver'),
		array('State'=>'Connecticut', 'contact'=> 'Hartford'),
		array('State'=>'Delaware', 'contact'=> 'Dover'),
		array('State'=>'Florida', 'contact'=> 'Tallahassee'),
		array('State'=>'Georgia', 'contact'=> 'Atlanta'),
		array('State'=>'Hawaii', 'contact'=> 'Honolulu'),
		array('State'=>'Idaho', 'contact'=> 'Boise'),
		array('State'=>'Illinois', 'contact'=> 'Springfield'),
		array('State'=>'Indiana', 'contact'=> 'Indianapolis'),
		array('State'=>'Iowa', 'contact'=> 'Des Moines'),
		array('State'=>'Kansas', 'contact'=> 'Topeka'),
		array('State'=>'Kentucky', 'contact'=> 'Frankfort'),
		array('State'=>'Louisiana', 'contact'=> 'Baton Rouge'),
		array('State'=>'Maine', 'contact'=> 'Augusta'),
		array('State'=>'Maryland', 'contact'=> 'Annapolis'),
		array('State'=>'Massachusetts', 'contact'=> 'Boston'),
		array('State'=>'Michigan', 'contact'=> 'Lansing'),
		array('State'=>'Minnesota', 'contact'=> 'Saint Paul'),
		array('State'=>'Mississippi', 'contact'=> 'Jackson'),
		array('State'=>'Missouri', 'contact'=> 'Jefferson City'),
		array('State'=>'Montana', 'contact'=> 'Helena'),
		array('State'=>'Nebraska', 'contact'=> 'Lincoln'),
		array('State'=>'Nevada', 'contact'=> 'Carson City'),
		array('State'=>'New Hampshire', 'contact'=> 'Concord'),
		array('State'=>'New Jersey', 'contact'=> 'Trenton'),
		array('State'=>'New Mexico', 'contact'=> 'Santa Fe'),
		array('State'=>'New York', 'contact'=> 'Albany'),
		array('State'=>'North Carolina', 'contact'=> 'Raleigh'),
		array('State'=>'North Dakota', 'contact'=> 'Bismarck'),
		array('State'=>'Ohio', 'contact'=> 'Columbus'),
		array('State'=>'Oklahoma', 'contact'=> 'Oklahoma City'),
		array('State'=>'Oregon', 'contact'=> 'Salem'),
		array('State'=>'Pennsylvania', 'contact'=> 'Harrisburg'),
		array('State'=>'Rhode Island', 'contact'=> 'Providence'),
		array('State'=>'South Carolina', 'contact'=> 'Columbia'),
		array('State'=>'South Dakota', 'contact'=> 'Pierre'),
		array('State'=>'Tennessee', 'contact'=> 'Nashville'),
		array('State'=>'Texas', 'contact'=> 'Austin'),
		array('State'=>'Utah', 'contact'=> 'Salt Lake City'),
		array('State'=>'Vermont', 'contact'=> 'Montpelier'),
		array('State'=>'Virginia', 'contact'=> 'Richmond'),
		array('State'=>'Washington', 'contact'=> 'Olympia'),
		array('State'=>'West Virginia', 'contact'=> 'Charleston'),
		array('State'=>'Wisconsin', 'contact'=> 'Madison'),
		array('State'=>'Wyoming', 'contact'=> 'Cheyenne')
	);

	$cmd = new MongoDB\Driver\Command(['listDatabases' => 1]);
	try {
		$result = $conn->executeCommand('admin', $cmd);
		$dbArray = $result->toArray()[0];
	} catch(MongoDB\Driver\Exception $e) {
		echo $e->getMessage().'<br />';
		exit;
	}

	if(!array_search('phpDemo', array_column($dbArray->databases, 'name'))){
		echo 'phpDemo database doesn\'t exist, creating it<br />';
		foreach($states AS $state){
			$row = new MongoDB\Driver\BulkWrite();
			$row->insert($state);
			$conn->executeBulkWrite('phpDemo.state', $row);
			echo '&emsp;Added '.$state['State'].'<br />';
		}
	} 

	$query = new MongoDB\Driver\Query([],[]);
	$result = $conn->executeQuery('phpDemo.state', $query);

	if($result){
		echo '<h3>Reading data from MongoDB</h3>'.
		'<table width="500" align="center">'.
			'<thead>'.
				'<tr><th>_id</th><th>State</th><th>contact</th></tr>'.
			'</thead>'.
			'<tbody>';
				foreach ($result as $rs){
					echo '<tr><td>'.$rs->{'_id'}.'</td><td>'.$rs->State.'</td><td>'.$rs->contact.'</td></tr>';
				}
			echo '</tbody>'.
		'</table>';
		unset($query, $result);
		
		$query = new MongoDB\Driver\Query(['State'=> 'Massachusetts'],[]);
		$result = $conn->executeQuery('phpDemo.state', $query);
		if($result){
			$rs = $result->toArray()[0];
			echo '<h3>Reading specific state from MongoDB</h3>'.
			'The capital of '.$rs->State.' is '.$rs->contact.'<br /><br />_id: '.$rs->{'_id'};
		}
	}
?>