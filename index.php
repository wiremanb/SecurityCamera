<?PHP
    $dir = 'vids';
    $videoElement = array();
    $imgElement = array();
    $videoLocations = array();
  if($handle = opendir($dir.'/')) {//        echo 'Looking inside \''.$dir.'\':<br>';
        while($file = readdir($handle)) {
            if($file != '.' && $file != '..') {
                $extension = substr($file, strpos($file, '.')+1);
//                echo $extension.'<br>';
                if($extension == "mp4" || $extension == "ogg" || $extension == "webm") {
                    $videoElement[] = '<p align="center"><video src="'.$dir.'/'.$file.'" type="video/ogg" controls></video></p><br><br>';
                    $videoLocations[] = $file;
                }
                if($extension == "jpg" || $extension == "jpeg" || $extension == "tiff" || $extension == "gif") {
                    $imgElement[] = '<p align="center"><img src="'.$dir.'/'.$file.'" width="640" height="480"></img></p><br><br>';
                }
                if($extension == "avi" || $extension == "flv" || $extension == "swf" || $extension == "mpg") {
                    $videoElement[] = '<embed type="application/x-vlc-plugin" pluginspage="http://www.videolan.org" version="VideoLAN.VLCPlugin.2" target="'.$dir.'/'.$file.'" width="640" height="480""></embed><br><br><br>';
                    $videoLocations[] = $file;
                }
            }
        }
    }
?>

<HTML>
    <head>
        <title>Security Camera Server</title>
        <style type="text/css">
            body { font-size: 80%; font-family: 'Lucida Grande', Verdana, Arial, Sans-Serif; }
            ul#tabs { list-style-type: none; margin: 30px 0 0 0; padding: 0 0 0.3em 0; }
            ul#tabs li { display: inline; }
            ul#tabs li a { color: #42454a; background-color: #dedbde; border: 1px solid #c9c3ba; border-bottom: none; padding: 0.3em; text-decoration: none; }
            ul#tabs li a:hover { background-color: #f1f0ee; }
            ul#tabs li a.selected { color: #000; background-color: #f1f0ee; font-weight: bold; padding: 0.7em 0.3em 0.38em 0.3em; }
            div.tabContent { border: 1px solid #c9c3ba; padding: 0.5em; background-color: #f1f0ee; }
            div.tabContent.hide { display: none; }
        </style>
        <h1>Security Camera Server</h1>
    </head>
    <script type="text/javascript">
    //<![CDATA[

    var tabLinks = new Array();
    var contentDivs = new Array();

    function init() {
        // Grab the tab links and content divs from the page
        var tabListItems = document.getElementById('tabs').childNodes;
        for ( var i = 0; i < tabListItems.length; i++ ) {
            if ( tabListItems[i].nodeName == "LI" ) {
                var tabLink = getFirstChildWithTagName( tabListItems[i], 'A' );
                var id = getHash( tabLink.getAttribute('href') );
                tabLinks[id] = tabLink;
                contentDivs[id] = document.getElementById( id );
            }
        }
        
        // Assign onclick events to the tab links, and
        // highlight the first tab
        var i = 0;
        
        for ( var id in tabLinks ) {
            tabLinks[id].onclick = showTab;
            tabLinks[id].onfocus = function() { this.blur() };
            if ( i == 0 ) tabLinks[id].className = 'selected';
            i++;
        }
        
        // Hide all content divs except the first
        var i = 0;
        
        for ( var id in contentDivs ) {
            if ( i != 0 ) contentDivs[id].className = 'tabContent hide';
            i++;
        }
        
        // Load the live feed, because we are loaded now.
        var lf = document.getElementById("liveFeed");
        lf.src = "http://192.168.1.2:8081";
        
        // Load list of videos
        getVideos();

	firstLoad();
    }

    function showTab() {
        var selectedId = getHash( this.getAttribute('href') );
        
        // Highlight the selected tab, and dim all others.
        // Also show the selected content div, and hide all others.
        for ( var id in contentDivs ) {
            if ( id == selectedId ) {
                tabLinks[id].className = 'selected';
                contentDivs[id].className = 'tabContent';
            } else {
                tabLinks[id].className = '';
                contentDivs[id].className = 'tabContent hide';
            }
        }
        
        // Stop the browser following the link
        return false;
    }

    function getFirstChildWithTagName( element, tagName ) {
        for ( var i = 0; i < element.childNodes.length; i++ ) {
            if ( element.childNodes[i].nodeName == tagName ) return element.childNodes[i];
        }
    }

    function getHash( url ) {
        var hashPos = url.lastIndexOf ( '#' );
        return url.substring( hashPos + 1 );
    }

    function getVideos() {
        var sel = document.getElementById("videoSelector");
        var x = <?php echo json_encode($videoLocations);?>;
        for(var i = 0; i < x.length; i++) {
            var option = document.createElement("option");
            option.text = x[i];
            option.value = "vids/" + x[i];
            sel.add(option);
        }
	firstLoad();
    }
    function updateSelector(event) {
        var x = document.getElementById("player");
	var y = document.getElementById("videoSelector");
        var txt = "vids/" + y.options[y.selectedIndex].text;
	x.setAttribute('data', txt);

    }
    function firstLoad() {
        var x = document.getElementById("player");
	var y = document.getElementById("videoSelector");
        x.data = "vids/" + y.options[1].text;
    }
    </script>
    <body onload="init()">
        <ul id="tabs">
            <li><a href="#live">Live Feed</a></li>
            <li onfocus="getVideos()"><a href="#previous">Previous Captures</a></li>
        </ul>
        <div class="tabContent" id="live">
            <h1 align="center">Live Feed</h1>
            <p align="center"><img id="liveFeed" width="640" height="480"></img></p>
        </div>
        <div class="tabContent" id="previous">
            <h1 align="center">Previous Captures</h1>
            <p align="left">
                <select id="videoSelector" onChange="updateSelector.call(this,event)"></select>
            </p>
            <p align="center">
		<object id="player" width="660" height="500"></object>
            </p>
        </div>
    </body>
</HTML>
