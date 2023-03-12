<?php
session_start();
	
if(!isset($_SESSION['logged']))
{
	header('Location: index.php');
	exit();
}

$h_goals=$_POST['h_goals'];    //przypisanie do zminnej liczby bramek wpisanej w pole w gole w main.php
$a_goals=$_POST['a_goals'];

if($h_goals==NULL or $a_goals==NULL)
{
	$_SESSION['alert_add_score']='Aby przesłać wynik należy uzupełnić obie rubryki!';
	header('Location: main.php');
	exit();
}

require_once "connect.php";

$connect= @new mysqli($db_domain,$db_login,$db_password,$db_name);    //połączenie do bazy
if($connect->connect_errno!=0) //sprawdzenie czy polaczenie sie nie udało
{
    echo "Error;".$connect->connect_errno;
}
else
{
	$id_match= $_GET['matchid'];
	
	if($h_goals>$a_goals)
	{
		$odd=$_GET['odd_1'];
	}
	else if($h_goals==$a_goals)
	{
		$odd=$_GET['odd_X'];
	}
	else
	{
		$odd=$_GET['odd_2'];
	}
	
	
    $sql_date=$sql="SELECT * FROM match_list WHERE id_match='$id_match'";
	if($result=@$connect->query($sql_date)){
		
	
	$time=time();  //odzczytanie czasu serwera

    

    $h_goals=htmlentities($h_goals,ENT_QUOTES,"UTF-8");    //zabezpieczenie czy dominik nie probuje oszukac
    $a_goals=htmlentities($a_goals,ENT_QUOTES,"UTF-8");
	
    
    $id=$_SESSION['id'];
    $row=$result->fetch_assoc();
    $date=$row['date'];		
	$date = date('Y-m-d H:i:s', strtotime($date.'+1 hour')); //konwersja daty
	$timetobet = date('Y-m-d H:i:s', strtotime($date.'-15 minutes'));
	$matchfinished = date('Y-m-d H:i:s', strtotime($date.'+2 hour'));
	$result->close();
	$sql_amount="SELECT * FROM bet";
	if($result4=@$connect->query($sql_amount))
	{
		$idbet=($result4->num_rows)+1;
		$result4->close();
	}
    $sql="SELECT * FROM bet WHERE id_match='$id_match' AND id_accounts='$id' ";
 
    if($time<$timetobet)
    {
        if($result1=@$connect->query($sql)) 
        {
            $bet_check=$result1->num_rows;
			$result1->close();
            if($bet_check>0) // ktos probował już jeden raz obstawiać, chce zmienić swoj bet
            {
				$sql_update="UPDATE bet SET result_bet_c1='$h_goals', result_bet_c2='$a_goals', odd='$odd'  WHERE id_match='$id_match' AND id_accounts='$id'";
				if($result2=@$connect->query($sql_update)){
				$_SESSION['alert_add_score']='Wynik zaktualizowany pomyślnie!';
				}
			}
            else
			{		
				$sql_add="INSERT INTO bet VALUES('$idbet','$id','$id_match','$h_goals','$a_goals','$date','$matchfinished','$odd',0)";
				if($result3=@$connect->query($sql_add))
				{ //pierwsza proba dodania
				$_SESSION['alert_add_score']='Wynik dodany pomyślnie!';
				}
			}         
        }
    }
		else
		{
		$_SESSION['alert_add_score']='Czas na obstawienie tego meczu minął :(';
		}
	}
else
	{
		$_SESSION['alert_add_score']='Błąd połączenia z bazą nie udało się przesłać wyniku ';
	}
    $connect->close();
	
}
	header('Location: main.php');
	exit();


?>