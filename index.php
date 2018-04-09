<html>
<link rel="stylesheet" href="styles.css">
<body>
<?php
/**
 * Created by PhpStorm.
 * User: Dale
 * Date: 2018/4/9
 * Time: 13:26
 */
    $hostname = "sql2.njit.edu";
    $username = "yl622";
    $password = "evPkHDGVf";
    $conn = NULL;
    try
    {
        $conn = new PDO("mysql:host=$hostname;dbname=$username", $username, $password);
        echo "Connected successfully<br>";
    }
    catch(PDOException $e)
    {
        // echo "Connection failed: " . $e->getMessage();
        http_error("500 Internal Server Error\n\n"."There was a SQL error:\n\n" . $e->getMessage());
    }
    // Runs SQL query and returns results (if valid)
    function runQuery($query) {
        global $conn;
        try {
            $q = $conn->prepare($query);
            $q->execute();
            $results = $q->fetchAll();
            $q->closeCursor();
            return $results;
        } catch (PDOException $e) {
            http_error("500 Internal Server Error\n\n"."There was a SQL error:\n\n" . $e->getMessage());
        }
    }
    function http_error($message)
    {
        header("Content-type: text/plain");
        die($message);
    }

    $query = "SELECT * FROM accounts WHERE id < 6";
    $result = runQuery($query);
    $count = count($result);
    print("I got $count results <br>");

    function print_table($array){
        print("<table>");
        foreach ($array as $line_num=>$line){
            if ($line_num == 0){
                print("<tr>");
                $count_col = 0;
                foreach ($line as $col_name => $columns){
                    if ($count_col%2 ==0){
                        print("<th>$col_name</th>");
                    }
                    $count_col++;
                }
                print("</tr>");
            }
            print("<tr>");
            $count_col = 0;
            foreach ($line as $col_name => $columns){
                if ($count_col%2 ==0) {
                    print("<th>$columns</th>");
                }
                $count_col++;
            }
            print("</tr>");
        }
        print("</table>");
    }
    print_table($result);
?>
</body>
</html>
