<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require_once('core/core.php');
    head();
    ?>
</head>

<body>

    <script type="text/javascript">
        const API_BASE = <?php echo json_encode($GLOBALS['API_BASE'] ?? ''); ?>;

        function loadStats(callback) {
            $.get(API_BASE + '/api/itemcounts', result => {
                //print the array
                document.write("<table>");

                for (var i = 0; i < result.length; i++) {
                    document.write("<tr>");
                    document.write("<td><img style = 'width:64px; height:64px' src = \"img/game/sprites/" + i + ".png\" </td>");
                    document.write("<td>" + result[i] + "</td>");
                    document.write("</tr>");
                }

                //document.write("Item Counts: " + result + "<br>");
            });





        }


        window.onload = () => {
            loadStats();
        };
    </script>

</body>

</html>