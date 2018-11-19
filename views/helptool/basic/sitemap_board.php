<?php
ob_start();

echo "<?xml version=\"1.0\" encoding=\"" . config_item('charset') . "\"?".">\n";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
if (element('list', element('data', $view))) {
	foreach (element('list', element('data', $view)) as $key => $row) {
		echo "<url>\n";
		echo "<loc>" . element('link', $row) . "</loc>\n";
		echo "</url>\n";
	}
}
echo "</urlset>\n";

$xml = ob_get_clean();

echo $xml;
