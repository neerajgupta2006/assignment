#!/usr/bin/php
<?php
$shortopts  = "";
$shortopts .= "h";
$shortopts .= "u:";  // Required value
$shortopts .= "p:"; //  required value
$shortopts .= "l:";
$shortopts .= "t:";
$shortopts .= "d:";

$username="";
$password="";
$url="";
$title="";
$description="";

$longopts  = array(
    "required:",     // Required value
    "optional::",    // Optional value
    "option",        // No value
    "opt",           // No value
);

$options = getopt($shortopts, $longopts); //taking variables from commandline

#assigning values
foreach (array_keys($options) as $opt) switch ($opt) {
  case 'u':
    $username = $options['u'];
	//check the required variable avaliblity
	if($username==''){	
		print_help_message();
		exit(1);
	}
    break;

  case 'p':
    $password = $options['p'];
	//check the required variable avaliblity
	if($password==''){	
		print_help_message();
		exit(1);
	}
    break;

  case 'l':
    $url = $options['l'];
    break;

  case 't':
    $title = $options['t'];
    break;

  case 'd':
    $description = $options['d'];
    break;

  case 'h':
    print_help_message();
    exit(1);
}

######################################
#Function Name : print_help_message
#Parameter Count : 0
#Functionality : explain script help
######################################
function print_help_message() {

    echo './create_issue.php -u "User Name" -p "Password" -l "URL" -t "Title" -d "Description"' . "\n";

}


class submitter {

        var $username="";
        var $password="";
        var $url="";
        var $title="";
        var $description="";
        var $curlUrl="";

        public function submitter() {

        }

        public function setParams($user, $pass, $url, $tit, $desc) {
		$username = $user;
		$password = $pass;
		$url=$url;
		$title = $tit;
		$description = $desc;
		//here we are checking the given url
		$parseUrl       = parse_url($url);
		$host           =  $parseUrl['host'];

		//selecting proper syntax for creating issue on basic of given url
		switch($host)
		{
        	case 'bitbucket.org' :
        	//build final url here
        	$curlUrl = "curl --user $username:$password $url --data 'title=$title&content=$description' ";
        	break;

        	case 'api.github.com' :
        	//build final url here
        	$curlUrl = "curl -u '$username:$password' $url -d '{\"title\":\"$title\", \"body\":\"$description\"}'";
        	break;

        	default :
        	$curlUrl = "curl -u '$username:$password' $url -d '{\"title\":\"$title\", \"body\":\"$description\"}'";


		}	
		return $curlUrl;	

        }
		
        public function submit($curlUrl) {
		exec($curlUrl);
        }

}

#initiate the submitter class
$submitIssue = new submitter();

#set parameters
$curlUrl2 = $submitIssue->setParams($username, $password, $url, $title, $description);

#create issue
$submitIssue->submit($curlUrl2);
?>
