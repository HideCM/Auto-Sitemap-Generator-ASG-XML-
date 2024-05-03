<?php

// Hàm lọc và loại bỏ các URL trùng lặp từ các tệp XML trong thư mục $directory và ghi log
function filter_duplicate_urls($directory)
{
    // Mảng lưu trữ các URL đã xuất hiện
    $seen_urls = [];

    // Đếm số lượng URL trùng lặp đã loại bỏ
    $duplicate_count = 0;

    // Mở hoặc tạo file log
    $log_file = fopen('dup_log.txt', 'a');

    // Lặp qua các tệp XML trong thư mục
    foreach (glob("$directory/*.xml") as $i => $file) {
        $xml = simplexml_load_file($file);

        // Kiểm tra xem tệp XML có hợp lệ hay không
        if ($xml !== false) {
            foreach ($xml->url as $index => $url) {
                // Lấy nội dung của thẻ <loc> trong từng URL
                $url_str = (string) $url->loc;

                // Nếu URL đã xuất hiện trước đó trong bất kỳ tệp nào, loại bỏ URL hiện tại
                if (in_array($url_str, $seen_urls)) {
                    unset($xml->url[$index]);
                    // Ghi log với thông tin về tệp XML
                    fwrite($log_file, "Tệp: $file đã trùng với tệp " . ($i - 1) . " | URL = $url_str\n");
                    $duplicate_count++;
                } else {
                    // Nếu URL chưa xuất hiện, thêm nó vào mảng seen_urls
                    $seen_urls[] = $url_str;
                }
            }

            // Lưu XML đã chỉnh sửa lại vào tệp
            $xml->asXML($file); // Lưu lại tệp XML sau khi đã loại bỏ các URL trùng lặp
        } else {
            echo "Lỗi khi tải tệp XML: $file. Tệp không hợp lệ.<br>";
        }
    }

    // Đóng file log
    fclose($log_file);

    return $duplicate_count;
}

// Thư mục chứa các tệp XML
$directory = "sitemap_data";

// Gọi hàm filter_duplicate_urls() với thư mục đã chỉ định
$duplicate_count = filter_duplicate_urls($directory);

// Xuất thông báo về số lượng URL trùng lặp đã loại bỏ
if ($duplicate_count > 0) {
    echo "Đã loại bỏ $duplicate_count URL trùng lặp.";
} else {
    echo "Không có URL trùng lặp được loại bỏ.";
}

?>
