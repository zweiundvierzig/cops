<?php
/**
 * COPS (Calibre OPDS PHP Server) HTML main script
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Sébastien Lucas <sebastien@slucas.fr>
 *
 */

    require_once ("config.php");
    require_once ("base.php");
    require_once ("author.php");
    require_once ("publisher.php");
    require_once ("serie.php");
    require_once ("tag.php");
    require_once ("language.php");
    require_once ("customcolumn.php");
    require_once ("book.php");
    require_once ("resources/doT-php/doT.php");

    // If we detect that an OPDS reader try to connect try to redirect to feed.php
    if (preg_match("/(MantanoReader|FBReader|Stanza|Marvin|Aldiko|Moon+ Reader)/", $_SERVER['HTTP_USER_AGENT'])) {
        header("location: feed.php");
        exit ();
    }

    $page = getURLParam ("page", Base::PAGE_INDEX);
    $query = getURLParam ("query");
    $qid = getURLParam ("id");
    $n = getURLParam ("n", "1");
    $database = GetUrlParam (DB);


    // Access the database ASAP to be sure it's readable, redirect if that's not the case.
    // It has to be done before any header is sent.
    Base::checkDatabaseAvailability ();

    if ($config ['cops_fetch_protect'] == "1") {
        session_start();
        if (!isset($_SESSION['connected'])) {
            $_SESSION['connected'] = 0;
        }
    }

    header ("Content-Type:text/html;charset=utf-8");
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default" />
    <link rel="apple-touch-icon" href="./icons/icon57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="./icons/icon72.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="./icons/icon114.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="./icons/icon144.png" />
    <meta name="msapplication-TileColor" content="#123456"/>
    <meta name="msapplication-TileImage" content="./icons/icon144.png"/>

    <title>COPS</title>

    <script type="text/javascript" src="<?php echo getUrlWithVersion("resources/jQuery/jquery-1.10.2.min.js") ?>"></script>
    <script type="text/javascript" src="<?php echo getUrlWithVersion("resources/jquery-cookie/jquery.cookies.js") ?>"></script>
    <script type="text/javascript" src="<?php echo getUrlWithVersion("resources/jquery-sortelements/jquery.sortElements.js") ?>"></script>
<?php if (!useServerSideRendering ()) { ?>
    <script type="text/javascript" src="<?php echo getUrlWithVersion("resources/Magnific-Popup/jquery.magnific-popup.min.js") ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo getUrlWithVersion("resources/Magnific-Popup/magnific-popup.css") ?>" media="screen" />
    <script type="text/javascript" src="<?php echo getUrlWithVersion("resources/doT/doT.min.js") ?>"></script>
    <script type="text/javascript" src="<?php echo getUrlWithVersion("resources/lru/lru.js") ?>"></script>
    <script type="text/javascript" src="<?php echo getUrlWithVersion("resources/typeahead/typeahead.js") ?>"></script>
<?php } ?>
    <script type="text/javascript" src="<?php echo getUrlWithVersion("util.js") ?>"></script>
    <link rel="related" href="<?php echo $config['cops_full_url'] ?>feed.php" type="application/atom+xml;profile=opds-catalog" title="<?php echo $config['cops_title_default']; ?>" />
    <link rel="icon" type="image/vnd.microsoft.icon" href="<?php echo $config['cops_icon']; ?>" />
    <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Open+Sans:400,300italic,800,300,400italic,600,600italic,700,700italic,800italic&subset=latin,cyrillic' />
    <link rel="stylesheet" type="text/css" href="<?php echo getUrlWithVersion("resources/normalize/normalize.css") ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo getUrlWithVersion("styles/font-awesome.css") ?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo getUrlWithVersion(getCurrentCss ()) ?>" media="screen" />
<?php if (!useServerSideRendering ()) { ?>
    <script type="text/javascript">

        $(document).ready(function() {
            initiateAjax ("<?php echo "getJSON.php?" . addURLParameter (getQueryString (), "complete", 1); ?>",
                          "<?php echo getCurrentTemplate (); ?>");
        });



    </script>
<?php } ?>
</head>
<body>
<?php
if (useServerSideRendering ()) {
    // Get the data
    $data = getJson (true);

    echo serverSideRender ($data);
}
?>
</body>
</html>