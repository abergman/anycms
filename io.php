<?php
require 'vendor/autoload.php';

//Base class and functions

Class anycms {

	public function __construct( /*...*/ ) {


	}

	public function count_posts(){
		$conn = r\connect('localhost');
		$conn->useDb('anycms');
		$result = r\table("posts")->count()->run($conn);
		

		print_r($result);
		return (float)$result;

	}

	public function response($status, $data){
		//http_response_code($status);
		echo json_encode(array('result' => $data));

	}

	public function create_post($author,$title,$body){
		if(!$author || !$title || !$body){
			echo "A parameter is missing";	
		}

		$document = array('author' => $author,'title' => $title,'body' => $body);
		$conn = r\connect('localhost');
                $conn->useDb('anycms');
                $result = r\table("posts")->insert($document)
        	->run($conn);	
                return $result;
	}

}

//Frontend Class and Functions
//anycms::response('200',anycms::count_posts());

	function count_posts(){
		echo anycms::response('200',anycms::count_posts());
	}

	function index(){
		echo "AnyCMS";
	}	

	function create_post($author,$title,$body){
		echo anycms::create_post($author,$title,$body);
	}

$klein = new \Klein\Klein();

$klein->respond('GET','/anycms/', function ($request) {
    index();
});

$klein->respond('GET','/anycms/posts/count', function ($request) {
    count_posts();
});

$klein->respond('POST','/anycms/posts/create/', function ($request) {
    	$author = $_POST['author'];
	$title = $_POST['title'];
	$content = $_POST['content'];

	create_post($author,$title,$content);
});

$klein->dispatch();
