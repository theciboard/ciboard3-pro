<?php
ob_start();

echo "<?xml version=\"1.0\" encoding=\"" . config_item('charset') . "\"?".">\n";
echo "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
if (element('sitemap', $view)) {
	foreach (element('sitemap', $view) as $key => $row) {
		echo "<sitemap>\n";
		echo "<loc>" . element('loc', $row) . "</loc>\n";
		echo "</sitemap>\n";
	}
}
echo "</sitemapindex>\n";

$xml = ob_get_clean();

echo $xml;
