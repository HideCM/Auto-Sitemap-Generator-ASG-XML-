<?php

// CONFIGURATION

// Set a password to limit access to generating XML's
$token = "1";

// Set your MySQL details 
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "dbname";

// Set your custom table name to generate 
$table_name  = "mac_vod";

// Set your custom column name to generate 
$column_name  = "vod_sub";
$column_name_title = "vod_name";
$column_name_duration = "vod_duration";

// Your site Base URL
$base_url = "https://domain.com/slug/";

// Base URL for sitemap files
$base_sitemap = "https://domain.com/sitemap-folder/";

$frequency = "weekly";

// Flag to control whether to generate sitemap with only the latest 100 rows (true = generate sitemap with only latest 100 rows, false = generate full sitemap)
$latest_100_rows_only = false;

// Number of URLs per chunk
$urls_per_chunk = 300; // row (Ex: 500)

// END CONFIGURATION

// Check token
if ($_GET["token"] != $token) {
    die("Not Authorized");
}

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Query to get data from the database
$query = "SELECT $column_name, $column_name_title, $column_name_duration FROM $table_name";

// If you only want to generate a sitemap with the latest 100 rows
if ($latest_100_rows_only) {
    $query .= " ORDER BY id DESC LIMIT 100"; // change 100 to your number
}

// Execute query
$result = mysqli_query($conn, $query);

$count = 0;

// Create directory if doesn't exist
if (!is_dir('sitemap_data')) {
    mkdir('sitemap_data');
}

// Browse the results from the database and add new URLs to the sitemap.xml file
while ($row = mysqli_fetch_assoc($result)) {
    $curr = $row[$column_name];
    $name = htmlspecialchars($row[$column_name_title], ENT_QUOTES, 'UTF-8');
    $duration = $row[$column_name_duration];
    $url = $base_url . $curr;

    // Generate filename for each chunk
    $file_name = 'sitemap_data/sitemap_chunk_' . ceil(($count + 1) / $urls_per_chunk) . '.xml';

    // Check if the chunk file exists or not, if not, create a new one
    if (!file_exists($file_name)) {
        $chunk_dom = new DomDocument('1.0', 'UTF-8');
        $chunk_root = $chunk_dom->createElement('urlset');
        $chunk_dom->appendChild($chunk_root);

        // Add xmlns attribute to the root node
        $attr = $chunk_dom->createAttribute('xmlns');
        $attr->value = 'http://www.sitemaps.org/schemas/sitemap/0.9';
        $chunk_root->appendChild($attr);
    } else {
        // If the chunk file already exists, load the content from that file
        $chunk_dom = new DomDocument();
        $chunk_dom->load($file_name);
        $chunk_root = $chunk_dom->documentElement;
    }

    // Create a new <url> element
    $url_element = $chunk_dom->createElement('url');

    // Add child elements
    $url_element->appendChild($chunk_dom->createElement('loc', htmlspecialchars($base_sitemap . $curr, ENT_QUOTES, 'UTF-8'))); // Escape special characters in URL
    $url_element->appendChild($chunk_dom->createElement('lastmod', date("Y-m-d")));
    $url_element->appendChild($chunk_dom->createElement('changefreq', $frequency));
    $url_element->appendChild($chunk_dom->createElement('priority', 1));

    // Create the video:title element
    $video_title = $chunk_dom->createElement('video:title', $name); // You can remove it if you don't want create video:title

    // Add namespace for video:title element and you can remove it if you don't want create video:title
    $video_title->setAttribute('xmlns:video', $url);
    $url_element->appendChild($video_title);

    // Add the <url> element to the chunk document
    $chunk_root->appendChild($url_element);

    // Save the contents of the chunk file
    $chunk_dom->formatOutput = true;
    $chunk_dom->save($file_name);

    $count++;
}

// Output a message about the total number of pages added to the sitemap (otherwise just generate a sitemap with the latest 100 rows)
if ($latest_100_rows_only) {
    echo "<strong>Đã tải 100 bản ghi gần nhất</strong><br>"; // Loaded 100 most recent records
} else {
    echo "<strong>Tổng số trang được thêm vào sitemap: $count</strong><br>"; // Total number of pages added to sitemap: $count
}

// Create sitemap_index.xml file to read small xml files
create_sitemap_index($count, $urls_per_chunk, $base_sitemap);

// Close the DB connection
mysqli_close($conn);

// Function to create sitemap_index.xml file
function create_sitemap_index($total_urls, $urls_per_chunk, $base_sitemap)
{
    $num_chunks = ceil($total_urls / $urls_per_chunk);
    $index_dom = new DomDocument('1.0', 'UTF-8');
    $sitemapindex = $index_dom->appendChild($index_dom->createElement('sitemapindex'));
    $attr = $index_dom->createAttribute('xmlns');
    $attr->value = 'http://www.sitemaps.org/schemas/sitemap/0.9';
    $sitemapindex->appendChild($attr);

    for ($i = 1; $i <= $num_chunks; $i++) {
        $sitemap = $index_dom->createElement('sitemap');
        $loc = $index_dom->createElement('loc', htmlspecialchars($base_sitemap . "sitemap_data/sitemap_chunk_$i.xml", ENT_QUOTES, 'UTF-8')); // Escape special characters in URL
        $lastmod = $index_dom->createElement('lastmod', date("Y-m-d"));
        $sitemap->appendChild($loc);
        $sitemap->appendChild($lastmod);
        $sitemapindex->appendChild($sitemap);
    }

    $index_dom->formatOutput = true;
    $index_dom->save('sitemap_index.xml');
}
?>
