<?php

# Names of the background and logo images.
$siteBackground="Background.gif";
$siteLogo="Logo.gif";

$linkDataFile="linkdata.txt";
$linkCaptions=array();
$linkOrder=array();
$linkTargets=array();

$excludeFile="exclude.txt";
$exclude=array();

# List of files to exclude from the link menu.
function loadExcludeList() {
  global $exclude, $excludeFile;
  if (!file_exists($excludeFile)) {
    return;
  }
  $exclude=file($excludeFile);
  $exclude=array_map("trim",$exclude);
}

# Get the correct link for a given filename or tag.
function getLinkTarget($fname) {
  if (substr($fname,0,1)=="_") {
    return "";
  }
  $ufile=urlencode($fname);
  $result="index.php?file=$ufile";
  $parts=array_map("trim",explode("=",$fname,2));
  if (count($parts)>1) $result=$parts[1];
  #echo "Link target of $fname is $result<br>\n";
  return $result;
}

# List of link names for files.
function loadLinkOrder() {
  global $linkCaptions, $linkOrder, $linkDataFile, $linkTargets;
  $linkOrder["ANYTHING"]=10000;
  if (!file_exists($linkDataFile)) {
    return;
  }
  $linkLines=file($linkDataFile);
  if ($linkLines!=FALSE) {
    $nextOrder=1;
    foreach ($linkLines as $linkLine) {
      if (trim($linkLine)=="") continue;
      list($fname,$linkText)=array_map("trim",explode("::",$linkLine,2));
      $linkOrder[$fname]=$nextOrder;
      ++$nextOrder;
      $linkCaptions[$fname]=$linkText;
      $linkTargets[$fname]=getLinkTarget($fname);
    }
  }
}

loadExcludeList();
loadLinkOrder();

# Return a string containing the last modification time of
# a given file.
function getLastModTime($fname) {
    if (file_exists($fname)) {
      return date("m-d-y H:i:s",filemtime($fname));
    } else {
      return "whenever...";
    }
}

# Get the contents of the right-hand column of each page.
function getRightMatter() {
  $siteRightMatter=implode("\n",file("right.phtml"));
  return "<em>Last changed: <br>" . getLastModTime(getContentFileName()) .
         "</em><br><br>" . $siteRightMatter;
}

# Add the right-hand column content to the output.
function insertRightMatter() { echo(getRightMatter()); }

# Get the common header content for the top of each page.
function getCommonHeader() {
  return implode("\n",file("header.phtml"));
}

# Get the common footer content for the top of each page.
function getCommonFooter() {
  return implode("\n",file("footer.phtml"));
}

# Insert the header into the output.
function insertCommonHeader() { echo(getCommonHeader()); }

# Insert the footer into the output.
function insertCommonFooter() { echo(getCommonFooter()); }

# Get the title for the given page. This is the site
# title, plus any custom link text we find in the file.
function getTitle() {
  $filename = getContentFileName();
  $linkText=getLinkText(getContentFileName());
  if ($linkText==$filename) {
    $linkText="";
  } else {
    $linkText=" : ".$linkText;
  }
  $siteTitle=implode("\n",file("title.phtml"));
  return $siteTitle.$linkText;
}

# Insert the title text into the output.
function insertTitle() { echo(getTitle()); }

# Get an IMG tag for the site logo.
function getLogo() {
  global $siteLogo;
  if (!file_exists($siteLogo)) return "";
  return "<img src=\"".$siteLogo."\">";
}

# Insert the logo into the output.
function insertLogo() { echo(getLogo()); }

# Get the background image attribute for the page BODY tag.
function getBackground() {
  global $siteBackground;
  return "background=\"".$siteBackground."\"";
}

# Insert the background attribute into the output.
function insertBackground() { echo(getBackground()); }

# Get the text of the link to the given file.
function getLinkText($filename) {
  global $linkCaptions;
  if (isset($linkCaptions[$filename])) {
    return $linkCaptions[$filename];
  }
  return substr($filename,0,strlen($filename)-5);
}

# Get the ordinal of the link to the given file.
function getLinkOrder($filename) {
  global $linkOrder;
  $order=$linkOrder["ANYTHING"];
  if (isset($linkOrder[$filename])) {
    $order=$linkOrder[$filename];
  }
  return $order;
}

# Get the link target for a particular content file.
function getLink($file) {
  #$ufile=urlencode($file);
  #return "index.php?file=$ufile";
  return getLinkTarget($file);
}

# Comparison function using link order.
function linkOrderCompare($file1,$file2) {
  $order1=getLinkOrder($file1);
  $order2=getLinkOrder($file2);

  if ($order1<$order2) return -1;
  if ($order1>$order2) return 1;
  
  $txt1=getLinkText($file1);
  $txt2=getLinkText($file2);
  if ($txt1>$txt2) return 1;
  if ($txt1<$txt2) return -1;
  
  return 0;
}

# Get the list of links, in order.
function getLinkFilesInOrder($path) {
  global $exclude,$linkTargets;
  $files=glob($path . "*.html");
  foreach ($files as $key => $file) {
    if (in_array($file,$exclude)) {
      unset($files[$key]);
   }
  }
  foreach ($linkTargets as $tag => $target) {
    #echo "Looking at *$tag*=>*$target*<br>\n";
    if (($tag!="ANYTHING") && !in_array($tag,$files)) {
      #echo "Adding $tag to file list<br>\n";
      array_push($files,$tag);
    }
  }
  usort($files,"linkOrderCompare");
  #echo "Final file list:<br>\n";
  #foreach ($files as $file) {
  #  echo "  *$file*<br>\n";
  #}
  return $files;
}

# Get the list of all links to content files.
function getLinkList($path) {
  $result = "<ul>\n";
  $fnames=getLinkFilesInOrder($path);
  foreach ($fnames as $file) {
    $text=getLinkText($file);
    $link=getLink($file);
    if ($link=="") {
      $result.="$text\n";
    } else {
      $result.="<li><a href=\"$link\">$text</a>\n";
    }
  }
  $result.="</ul>\n";
  return $result;
}

# Insert the link list into the output.
function insertLinkList($path) { echo(getLinkList($path)); }

# Get the name of the content file being rendered.
function getContentFileName() {
  if (isset($_GET['file'])) {
    return $_GET['file'];
  } else {
    return 'home.html';
  }
}

# Insert the content file into the output.
function insertFileContent() {
  echo("\n\n<!-- BEGIN LITTLESITE CONTENT -->\n");
  echo("<div name=\"LS_CONTENT\">\n");
  $fname=getContentFileName();
  if (file_exists($fname)) {
    include($fname);
  } else if (file_exists("404.html")) {
    readfile("404.html");
  } else {
    echo("<H2>Sorry, the requested file does not exist.</H2>");
  }
  echo("</div>\n");
  echo("<!-- END LITTLESITE CONTENT -->\n\n\n");
}

# Close the opening PHP tag.
?>
