<?php
//////////////////////////////////////////////////////////////////////

//Create the query dynamically
//PDO doesn't have a functionality for this out of the box, but it can ease the task significantly, thanks again to its ability to bind the arbitrary number of parameters in the form of array passed to execute(). So we can use a conditional statement which will add a condition to the query and also a parameter to bind:

// always initialize a variable before use!
$conditions = [];
$parameters = [];

// conditional statements
if (!empty($_GET['name']))
{
    // here we are using LIKE with wildcard search
    // use it ONLY if really need it
    $conditions[] = 'name LIKE ?';
    $parameters[] = '%'.$_GET['name']."%";
}

if (!empty($_GET['sex']))
{
    // here we are using equality
    $conditions[] = 'sex = ?';
    $parameters[] = $_GET['sex'];
}

if (!empty($_GET['car']))
{

    // here we are using not equality
    $conditions[] = 'car != ?';
    $parameters[] = $_GET['car'];
}

if (!empty($_GET['date_start']) && $_GET['date_end'])
{

    // BETWEEN
    $conditions[] = 'date BETWEEN ? AND ?';
    $parameters[] = $_GET['date_start'];
    $parameters[] = $_GET['date_end'];
}

// the main query
$sql = "SELECT * FROM users";

// a smart code to add all conditions, if any
if ($conditions)
{
    $sql .= " WHERE ".implode(" AND ", $conditions);
}

// the usual prepare/execute/fetch routine
$stmt = $pdo->prepare($sql);
$stmt->execute($parameters);
$data = $stmt->fetchAll();

//////////////////////////////////////////////////////////////////////

//Create a static query with all conditions at once
//We can actually use a conditional statement right in the query. A condition like (name = :name or :name is null) will return true if either the value matches the field's contents or if the value is null. It means that if we want the query to bypass a condition, we just have to pass null for the value. ong story short, here it goes:

$parameters['name'] = !empty($_GET['name']) ? "%".$_GET['name']."%" : null;
$parameters['sex']  = !empty($_GET['sex'])  ? $_GET['sex']  : null;
$parameters['car']  = !empty($_GET['car'])  ? $_GET['car']  : null;

$sql = "SELECT * FROM users 
WHERE (name LIKE :name or :name is null)
AND   (sex  = :sex  or :sex  is null)
AND   (car  != :car  or :car  is null)";

$stmt = $pdo->prepare($sql);
$stmt->execute($parameters);
$data = $stmt->fetchAll();
//The only drawback here is that this code will work only if emulation is turned off. So, to make it universal, we have to make it a little bit more verbose

$param['name'] = $param['name1'] = !empty($_GET['name']) ? "%".$_GET['name']."%" : null;
$param['sex'] = $param['sex1']  = !empty($_GET['sex'])  ? $_GET['sex']  : null;
$param['car'] = $param['car1']  = !empty($_GET['car'])  ? $_GET['car']  : null;

$sql = "SELECT * FROM users 
WHERE (name LIKE :name or :name1 is null)
AND   (sex  = :sex  or :sex1  is null)
AND   (car  != :car  or :car1  is null)";

$stmt = $pdo->prepare($sql);
$stmt->execute($param);
$data = $stmt->fetchAll();
?>
