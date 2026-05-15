<?php

// Config
include( '/home/skeptoidcom/public_html/teachkit.app/includes/config.inc.php' );

// Receive inbound toolkit_id
if ( isset( $_POST[ 'toolkit_id' ] ) ) {
    $toolkit_id = preg_replace( "/[^a-z0-9]+/", '', strtolower( $_POST[ 'toolkit_id' ] ) );
    if ( strlen( $toolkit_id ) == 4 ) {
        $q = "select * from toolkits where toolkit_id='$toolkit_id'";
        $r = mysql_query( $q );
        if ( mysql_num_rows( $r ) == 0 ) {
            $error_message = 'You did not enter a valid program code.';
        } else {
            $row = mysql_fetch_array( $r );
            if ( date( 'Y-m-d' ) > $row[ 'expire' ] ) {
                $error_message = 'The program you selected is expired.';
            } elseif ( $row[ 'status' ] != 'live' ) {
                $error_message = 'You did not enter a valid program code.';
            } else {
                header( "Location: /$toolkit_id" );
                exit;
            }
        }
    }
}

?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <title>Teachers Toolkit</title>
    <link href="/style.css" rel="stylesheet" type="text/css">
    <meta name="robots" content="noindex, follow">
    <meta property="og:url" content="https://teachkit.app">
    <meta property="og:site_name" content="Teachers Toolkit">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Skeptoid Teachers Toolkit">
    <meta property="og:description" content="Skeptoid's Teachers Toolkit provides an easy way for educators to share selected Skeptoid podcast episodes with a classroom.">
    <meta property="og:image" content="https://skeptoid.com/images/1400.png">
</head>

<body onLoad="self.focus(); document.go_form.toolkit_id.focus();">
    <div id="container">
        <header>
            <table width="100%">
                <tr>
                    <td width="10%">&nbsp;</td>
                    <td width="80%" align="center" valign="top">
                        <h1><a href="/" style="text-decoration: none; color: white;">Teachers Toolkit</a></h1>
                        <p style="color: white; margin: 0;" class="smaller">Educational media sharing platform</p>
                    </td>
                    <td width="10%" align="right" valign="top"><a href="/about.php" class="smaller" style="color: white; margin: 5px 10px;">About</a>
                    </td>
                </tr>
            </table>
        </header>
        <div id="body">
            <div class="blockwrapper">
                <div class="block">
                    <p>&nbsp;</p>
                    <form method="post" name="go_form" action="/" style="text-align: center;">
                        <?php

                        if ( isset( $error_message ) ) {
                            echo '<span class="redital">';
                            echo $error_message;
                            echo '</span>';
                            echo '<br><br>';
                        }

                        ?>Enter a 4-character program code:<br>
                        <div style="border: 1px solid gray; border-radius: 10px; margin: 10px auto 5px auto; display: inline-block; background-color: #F9F9F9;">
                            <table>
                                <tr>
                                    <td width="25">&nbsp;</td>
                                    <td align="right" valign="middle" nowrap="nowrap" class="big_entry" style="color: #666;">teachkit.app/</td>
                                    <td align="right" valign="middle" nowrap="nowrap"><input name="toolkit_id" type="text" autofocus class="big_entry" id="toolkit_id" tabindex="0" autocomplete="off" value="" spellcheck="false" autocorrect="off" autocapitalize="off" style="border: none; border-color: transparent; outline: none; background: transparent; text-align: left; margin: 0; padding: 0; -webkit-appearance: none; text-transform: lowercase;" size="4" maxlength="4">
                                    </td>
                                </tr>
                            </table>
                        </div><br><input type="submit" value="Go" class="cool_button">
                    </form>
                    <p>&nbsp;</p>
                    <p>Teachers Toolkit is a free tool for easy classroom sharing of selected <a href="https://skeptoid.com" target="_blank">Skeptoid podcast</a> episodes. Glob</p>
                    <h2>Students</h2>
                    <p>If you've been given a 4-character program code by your teacher, enter it above to access the content.</p>
                    <h2>Teachers</h2>
                    <p><a href="/about">Click here</a> to learn more about this tool.</p>
                    <p>See an example of what your students will see: <a href="/abcd">teachkit.app/abcd</a>
                    </p>
                    <p>If you are a teacher and would like to use this tool, visit us at <a href="https://skeptoid.com/teachers" target="_blank">skeptoid.com/teachers</a>
                    </p>
                    <p>&nbsp;</p>
                </div>
            </div>
        </div>
        <?php include('/home/skeptoidcom/public_html/skeptoid.org/includes/footer.html'); ?>
    </div>
</body>
</html>