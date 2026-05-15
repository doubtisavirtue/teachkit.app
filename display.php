<?php

// Config
include( 'includes/config.inc.php' );

// Param
$toolkit_id = preg_replace( '/[^a-z0-9 ]/', '', strtolower( $_GET[ 'id' ] ) );

// Validate
if ( strlen( $toolkit_id ) == 4 ) {
    $q = "select * from toolkits where toolkit_id='$toolkit_id'";
    $r = mysql_query( $q );
    if ( mysql_num_rows( $r ) == 0 ) {
        header( "Location: /" );
        exit;
    } else {
        $row = mysql_fetch_array( $r );
        if ( date( 'Y-m-d' ) > $row[ 'expire' ] || $row[ 'status' ] != 'live' ) {
            header( "Location: /" );
            exit;
        }
    }
}

?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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

<body>
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
                <div class="block" style="text-align: center;">
                    <h2>
                        <?=$row['public_title']?>
                    </h2>
                    <p class="descrip">
                        <?php

                        echo $row[ 'teacher_class' ];
                        if ( $row[ 'teacher_class' ] != '' )echo '<br>';

                        ?>Available until
                        <?=date('j M, Y', strtotime($row['expire']))?><br>Program
                        <strong>
                            <?=$toolkit_id?>
                        </strong>
                    </p>
                </div>
                <?php

                $q = "SELECT * 
                    FROM toolkit_episodes
                    LEFT JOIN episodes ON toolkit_episodes.episode_id = episodes.id
                    WHERE toolkit_id = '$toolkit_id'
                    ORDER BY sort_order";
                $r = mysql_query( $q );
                if ( mysql_num_rows( $r ) > 0 ) {
                    while ( $ep = mysql_fetch_array( $r ) ) {
                        $time = explode( ':', $ep[ 'duration' ] );
                        $minutes = $time[ 0 ] + round( $time[ 1 ] / 60, 0 );

                        ?>
                <div class="block">
                    <img src="https://skeptoid.com/eps1200x744/<?=$ep['id']?>.jpg" alt="" class="img">
                    <h2>
                        <?=$ep['title']?>
                    </h2>
                    <p class="details"><span style="white-space: nowrap;">Skeptoid Podcast</span> &bull; <span style="white-space: nowrap;">Episode <?=$ep['episode_number']?></span> &bull;
                        <span style="white-space: nowrap;">
                            <?=date('M Y', strtotime($ep['pub_date']))?>
                        </span> &bull;
                        <span style="white-space: nowrap;">
                            <?=$minutes.' '?>minutes</span>
                    </p>
                    <p class="descrip">
                        <?=$ep['subtitle'].' '?><a href="/transcripts/<?=$ep['id']?>" target="_blank">Transcript</a>
                    </p>
                    <?php if($ep['description'] != '') { ?>
                    <p class="details indent">
                        <?=$ep['description']?>
                    </p>
                    <?php } ?>
                    <audio controls preload="none">
                        <source src="https://skeptoid.com/toolkit/<?=$ep['id']?>,<?=$_COOKIE['u']?>,<?=$toolkit_id?>.mp3" type="audio/mp3">
                        <p class="grayital">Your browser does not support the audio tag.</p>
                    </audio>
                
                </div>
                <?php }} else { ?>
                <div class="block">
                    <p class="details">This toolkit does not have any content.</p>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php include('/home/skeptoidcom/public_html/skeptoid.org/includes/footer.html'); ?>
    </div>
</body>
</html>