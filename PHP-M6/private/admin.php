<?php
/**
 * Landing page for administrator.
 * Administrator is able to block/unblock other users, and send bulk email
 */

    $title="Administration";
    require_once "includes/security.php";
    include_once "../public/includes/autoload.php";  
    require_once "includes/header-admin.php";
    // require_once "../../includes/timeFromNow.php";
    date_default_timezone_set('Asia/Singapore');
?>

<h3>Welcome <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h3>

<?php

    if (isset($_GET['keywords'])) {

        $result = Usermanager::searchUsers($_GET['keywords'], $_SESSION['userID']);

        //Get all categories
        if (!(empty($result))) {
            foreach($result as $result2) {
                $cat[] = $result2['CATEGORY'];
            }
            $j        = 0;
            $category = array();
            
            //Get unique categories
            for($i=0; $i<count($cat); $i++) {
                if ($i == 0) {
                    $category[$j] = $cat[$i];
                } else {
                    if ($category[$j] <> $cat[$i]) {
                        $category[++$j] = $cat[$i];
                    }
                }
                $i++;
            }
        }

        echo '<br><br><h4 style="background-color:orange;color:white;padding:10px;">' .
             'Your Search Results for <i>\'' . htmlspecialchars($_GET['keywords']) . '\'</i></h4>';

        echo '<table class="search-result">';
        
        if (!(empty($result))) {
            for($k=0; $k<count($category); $k++) {
                echo '<tr class="blank-row"><td></td></tr>';
                echo '<tr><td><b>Search results under ' . $category[$k] . ':</b><br></td></tr>';
                $i = 0; 
                foreach($result as $arr) {
                    if ($arr['CATEGORY'] == $category[$k]) {                    
                        echo '<form method="POST" action="profile_view.php">';
                        echo '<tr><td>' . ++$i . ') ' .
                             '<input type="hidden" name="profile-id" value="' . $arr['userID'] . '">' .
                             '<input class="btn btn-sm btn-outline" type="submit" name="submit" value="View Profile">' .
                             '&nbsp;&nbsp;' .
                             '<b style="color:#2B8F7D;">' . $arr['FullName'] . '</b>, ' . 
                             '<i>' . $arr['countryName'] . '</i>, ' .
                             $arr['jobPosition'];
                        echo '<input type="hidden" name="search-keywords" value="' . htmlspecialchars($_GET['keywords']) . '">';
                        echo '</form>';
                        echo '</td></tr>';
                    }
                }
                $i = 0;
            }
        } else {
            echo '<tr class="blank-row"><td></td></tr>';
            echo '<tr><td><b><i>No search results.</i></b><br></td></tr>';
        }
        echo '</table>';
    } else {
?>

<?php } ?>

  </div> <!-- close main content -->

<?php include "../../includes/footer.php"; ?>

</body>
</html>
