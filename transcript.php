<?php

// Get ID
$id = preg_replace( '/[^0-9]/', '', $_GET[ 'id' ] ?? '' );
if ( !$id )header( "Location: https://teachkit.app" );
if ( $id < 4001 || $id > 4999 )header( "Location: https://teachkit.app" );

// Config
include( 'includes/config.inc.php' );

// Retrieve this article
$query = "select * from episodes where id='$id'";
$result = mysql_query( $query )or die( mysql_error() );
if ( mysql_num_rows( $result ) != 1 )header( "Location: https://teachkit.app" );
$row = mysql_fetch_array( $result );
$title = htmlentities( $row[ 'title' ] );
$subtitle = $row[ 'subtitle' ];
$keywords = $row[ 'keywords' ];
$episode_number = $row[ 'episode_number' ];
$pub_date = $row[ 'pub_date' ];
$author = $row[ 'author' ];
$html = $row[ 'html' ];
$this_url = 'skep.us/' . $id;
$author_shortcut = preg_replace( "/[^a-z]/", '', strtolower( $author ) );

// Redirect clever boys if this episode hasn't come out yet
if ( empty( $_COOKIE[ 'skeptoid_id' ] ) ) {
    if ( $pub_date > ( date( "Y-m-d" ) . ' 07:00:00' ) )header( 'Location: https://teachkit.app' );
}

?>
<!DOCTYPE html>
<html>
<head>
<title>
<?=htmlspecialchars($title)?>
</title>
<link rel="canonical" href="https://skeptoid.com/episodes/<?=$id?>">
<link rel="stylesheet" href="/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="<?=htmlspecialchars($subtitle)?>">
<meta name="robots" content="noindex">
<meta property="og:url" content="https://teachkit.app/transcripts/<?=$id?>">
<meta property="og:site_name" content="Teachers Toolkit">
<meta property="og:type" content="article">
<meta property="og:title" content="<?=htmlspecialchars($title)?>">
<meta property="og:description" content="<?=htmlspecialchars($subtitle)?>">
<meta property="og:image" content="https://skeptoid.com/eps1200x744/<?=$id?>.jpg">
</head>

<body>
<div style="padding: 0 20px;">
	<table style="margin: 0 auto;">
		<tr>
			<td align="left" valign="top" style="max-width: 750px;"><div style="word-break: break-word;">
					<p>&nbsp;</p>
					<h1>
						<?=$title?>
					</h1>
					<p class="grayital">
						<?=$subtitle?><br>from Skeptoid podcast <?='#'.$episode_number?>
					</p>
					<p>by
						<?=$author?>
					</p>
					<div>
						<?php

						// Display the image
						echo '<img src="https://skeptoid.com/eps1200x744/ep' . $episode_number . '.jpg" style="width: 100%;" alt="' . $title . '" class="noprint">';

						// Get the episode text
						$the_html = file_get_contents( '/home/skeptoidcom/public_html/html/' . $html );
						$the_html = str_replace( "\n", "", $the_html );
						$the_html = str_replace( "</p>", "</p>\n", $the_html );

						// Output the episode text
						echo $the_html;

						?>
						<p class="grayital">by
							<?=$author?>
						</p>
					</div>
					<p>&nbsp;</p>
					<?php

					// References
					$query = "select * from refs where id='$id' and status='Approved' order by authors";
					$result = mysql_query( $query );
					if ( mysql_num_rows( $result ) > 0 ) {

					    echo '<blockquote><h2>References &amp; Further Reading</h2><span class="smaller">';

					    while ( $row = mysql_fetch_assoc( $result ) ) {
					        echo '  <p>';
					        include( '/home/skeptoidcom/public_html/admin/includes/spew_ref.php' );
					        echo "</p>\n";
					    }

					    echo '</span></blockquote>';

					}

					?>
					<p>&nbsp;</p>
					<p class="grayital">Copyright
						<?='&copy;'.date('Y')?>
						Skeptoid Media, Inc. All Rights Reserved.</p>
				</div></td>
		</tr>
	</table>
	<p>&nbsp;</p>
</div>
</body>
</html>