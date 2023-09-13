<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>friends</title>

    <link rel="stylesheet" href="friends.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <header></header>
    <main>
        <div id="setting">
            <h1>Friends</h1>
            <ul>
                <li><i class="fa-solid fa-user-group"></i>Home</li>
                <li><i class="fa-solid fa-user-group"></i>Friend requests</li>
                <li><i class="fa-solid fa-user-group"></i>Suggestions</li>
                <li><i class="fa-solid fa-user-group"></i>All Friends</li>
            </ul>
        </div>

        <div id="friends-profile">
            <h2>Friend requests</h2>
            <div id="requests">
                <figure>
                    <img src="image/ganpati-profile.jpg" alt="profile" width="207" height="207" href="profile.html">
                    <figcaption>
                        <h3>Name</h3>
                        <button name="Confirm">Confirm</button>
                        <button>Delete</button>
                    </figcaption>
                </figure>
            </div>


        <hr>
        <h2>People you may know</h2>
        <?php
            $username123 = "Venkatesh Thevar";
            include "../../learning/tappa_Technical/connection.php";

            $sql = "SELECT name FROM fbLogin";

            $stmt = $database->prepare($sql);
            $stmt->execute();
            // $results = $stmt->fetch(PDO::FETCH_ASSOC);
            // print_r($results);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $singleName = $row['name'];
                $html = "<div id='people-may-know'>
                <figure>
                    <img src='image/ganpati-profile.jpg' alt='profile' width='207' height='207'>
                    <figcaption>
                        <h3>$singleName</h3>
                        <button>Confirm</button>
                        <button>Remove</button>
                    </figcaption>
                </figure>
            </div>";

                echo $html;
            }
        ?>
        <div id="people-may-know">
            <figure>
                <img src="image/ganpati-profile.jpg" alt="profile" width="207" height="207">
                <figcaption>
                    <h3>Name</h3>
                    <button>Add friend</button>
                    <button>Remove</button>
                </figcaption>
            </figure>


            <figure>
                <img src="image/ganpati-profile.jpg" alt="profile" width="207" height="207">
                <figcaption>
                    <h3>Name</h3>
                    <button>Add friend</button>
                    <button>Remove</button>
                </figcaption>
            </figure>
        </div>
        </div>
    </main>
    <footer></footer>
</body>
</html>