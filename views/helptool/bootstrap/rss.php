<?php
ob_start();
echo "<?xml version=\"1.0\" encoding=\"" . config_item('charset') . "\"?".">\n";
echo "<rss version=\"2.0\">\n";
echo "<channel>\n";
echo "<title>" . html_escape(element('title', $view)) . "</title>\n";
echo "<link>" . element('url', $view) . "</link>\n";
if (element('copyright', $view)) {
	echo "<copyright><![CDATA[ " . html_escape(element('copyright', $view)) . "]]></copyright>";
}
if (element('description', $view)) {
	echo "<copyright><![CDATA[ " . html_escape(element('description', $view)) . "]]></copyright>";
}
if (element('list', element('data', $view))) {
	foreach (element('list', element('data', $view)) as $key => $row) {
		echo "<item>\n";
		echo "<title><![CDATA[" . element('post_title', $row) . "]]></title>\n";
		echo "<link>" . element('link', $row) . "</link>\n";
		echo "<author>" . html_escape(element('author', $row)) . "</author>\n";
		echo "<pubDate>" . element('pubdate', $row) . "</pubDate>\n";
		if (element('content', $row)) {
			echo "<description><![CDATA[" . element('content', $row) . "]]></description>\n";
		}
		if (element('category', $row)) {
			echo "<category>" . html_escape(element('category', $row)) . "</category>\n";
		}
		echo "</item>\n";
	}
}
echo "</channel>\n";
echo "</rss>\n";

$xml = ob_get_clean();

echo $xml;
